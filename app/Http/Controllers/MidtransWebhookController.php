<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kausa;
use App\Models\TransaksiPembayaran;
use App\Services\NotifikasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransWebhookController extends Controller
{
    /**
     * Menangani callback webhook resmi dari Midtrans payment gateway.
     */
    public function handle(Request $request): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');
        $transactionStatus = (string) $request->input('transaction_status');
        $fraudStatus = (string) $request->input('fraud_status');

        $serverKey = (string) config('services.midtrans.server_key');
        if (empty($serverKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Midtrans server key is not configured.',
            ], 500);
        }

        // 1. Verifikasi SHA512 Signature dengan hash_equals (constant-time)
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        if (! hash_equals($expectedSignature, $signatureKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature key.',
            ], 403);
        }

        // 2. Temukan record donasi berdasarkan order_id / pesanan_pembayaran
        $donasi = Donasi::where('pesanan_pembayaran', $orderId)
            ->with(['kausa.instansi'])
            ->first();

        if (! $donasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Donasi dengan kode tersebut tidak ditemukan.',
            ], 404);
        }

        // 3. Pengecekan Idempotensi: jika sudah berhasil, jangan proses ulang
        if ($donasi->status === Donasi::STATUS_SUCCESS || $donasi->status === 'berhasil') {
            return response()->json([
                'status' => 'success',
                'message' => 'Donasi sudah diproses sebelumnya (idempotent).',
            ], 200);
        }

        // 4. Proses berdasarkan status transaksi Midtrans
        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            DB::transaction(function () use ($donasi, $request) {
                $donasiLocked = Donasi::where('id', $donasi->id)
                    ->lockForUpdate()
                    ->first();

                if ($donasiLocked->status === Donasi::STATUS_SUCCESS || $donasiLocked->status === 'berhasil') {
                    return;
                }

                $kausaLocked = Kausa::where('id', $donasiLocked->kausa_id)->lockForUpdate()->first();

                $donasiLocked->update([
                    'status' => Donasi::STATUS_SUCCESS,
                    'dibayar_pada' => now(),
                ]);

                // Akumulasi dana terkumpul kausa secara atomik
                $kausaLocked->tambahDanaTerkumpul((float) $donasiLocked->nominal);

                // Update / create TransaksiPembayaran
                $donasiLocked->transaksiPembayaran()->updateOrCreate(
                    ['donasi_id' => $donasiLocked->id],
                    [
                        'penyedia' => 'midtrans',
                        'referensi_penyedia' => $request->input('transaction_id', $donasiLocked->pesanan_pembayaran),
                        'status' => 'success',
                        'dibayar_pada' => now(),
                        'respons_penyedia' => $request->all(),
                    ]
                );

                // Notifikasi ke Donatur jika terdaftar
                if ($donasiLocked->user_id) {
                    NotifikasiService::kirim(
                        $donasiLocked->user_id,
                        'donasi_berhasil',
                        'Pembayaran Donasi Berhasil',
                        'Terima kasih! Donasi sebesar Rp '.number_format($donasiLocked->nominal, 0, ',', '.')." untuk kausa '{$donasiLocked->kausa->judul}' telah berhasil diterima.",
                        route('donatur.dashboard')
                    );
                }

                // Notifikasi ke Instansi pemilik kausa
                $instansiUser = $donasiLocked->kausa->instansi?->user;
                if ($instansiUser) {
                    NotifikasiService::kirim(
                        $instansiUser->id,
                        'donasi_masuk',
                        'Donasi Baru Diterima',
                        'Donasi sebesar Rp '.number_format($donasiLocked->nominal, 0, ',', '.')." diterima via Midtrans untuk kausa '{$donasiLocked->kausa->judul}'.",
                        route('dashboard.instansi.detail', $donasiLocked->kausa_id)
                    );
                }
            });
        } elseif (in_array($transactionStatus, ['cancel', 'deny'], true)) {
            $donasi->update(['status' => Donasi::STATUS_FAILED]);
        } elseif ($transactionStatus === 'expire') {
            $donasi->update(['status' => Donasi::STATUS_EXPIRED]);
        } elseif ($transactionStatus === 'pending') {
            $donasi->update(['status' => Donasi::STATUS_PENDING]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook Midtrans berhasil diproses.',
        ], 200);
    }
}
