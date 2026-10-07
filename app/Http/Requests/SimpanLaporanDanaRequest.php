<?php

namespace App\Http\Requests;

use App\Models\Kausa;
use Illuminate\Foundation\Http\FormRequest;

class SimpanLaporanDanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $isInstansi = ($user->role === 'instansi' || $user->peran === 'institution_user');
        if (! $isInstansi || ! $user->instansi) {
            return false;
        }

        // Jika ada kausa_id, pastikan kausa milik instansi ini
        if ($this->filled('kausa_id')) {
            $kausa = Kausa::find($this->input('kausa_id'));
            if (! $kausa || $kausa->instansi_id !== $user->instansi->id) {
                return false;
            }
        }

        return true;
    }

    protected function prepareForValidation(): void
    {
        // Sanitasi nominal rincian jika ada format titik/koma
        if ($this->has('rincian') && is_array($this->rincian)) {
            $cleaned = [];
            foreach ($this->rincian as $index => $item) {
                if (isset($item['nominal']) && is_string($item['nominal'])) {
                    $item['nominal'] = str_replace(['.', ','], ['', '.'], $item['nominal']);
                }
                $cleaned[$index] = $item;
            }
            $this->merge(['rincian' => $cleaned]);
        }
    }

    public function rules(): array
    {
        return [
            'action' => ['nullable', 'in:draft,submit'],
            'kausa_id' => ['required', 'integer', 'exists:kausa,id'],
            'judul' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string', 'max:1000'],
            'periode_mulai' => ['required', 'date'],
            'periode_selesai' => ['required', 'date', 'after_or_equal:periode_mulai'],
            'rincian' => ['required', 'array', 'min:1'],
            'rincian.*.uraian' => ['required', 'string', 'max:255'],
            'rincian.*.nominal' => ['required', 'numeric', 'min:1', 'max:9999999999999.99'],
            'rincian.*.tanggal_pengeluaran' => ['required', 'date'],
            'rincian.*.penerima_manfaat' => ['nullable', 'string', 'max:255'],
            'rincian.*.keterangan' => ['nullable', 'string', 'max:500'],
            'bukti' => ['nullable', 'array'],
            'bukti.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
