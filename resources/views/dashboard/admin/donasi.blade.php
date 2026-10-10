@extends('layouts.dashboard-admin')

@section('title', 'Rekap Donasi & Pembayaran')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1.5" aria-label="Breadcrumb">
                <a href="{{ route('dashboard.admin') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Admin</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <span class="text-slate-800 font-semibold">Rekap Donasi &amp; Kas Masuk</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#123B32] tracking-tight">
                Rekapitulasi Donasi &amp; Pembayaran Masuk
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-3xl">
                Audit mutasi donasi real-time masyarakat melalui simulasi QRIS dan Virtual Account Bank Jatim resmi Kas Daerah Pemkab Tulungagung.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors shadow-2xs">
                <i data-lucide="printer" class="w-4 h-4 text-slate-500"></i>
                Cetak Rekap Kas
            </button>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Dana Masuk</span>
                <i data-lucide="wallet" class="w-4 h-4 text-[#087F5B]"></i>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-[#087F5B] font-heading">
                Rp {{ number_format($stats['totalBerhasil'], 0, ',', '.') }}
            </p>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 block">&bull; Sah Tervalidasi Kas Daerah</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Transaksi Sukses</span>
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $stats['countBerhasil'] }}</p>
            <span class="text-[10px] text-slate-500 mt-1 block">Donasi Berhasil Selesai</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Verifikasi Manual</span>
                <i data-lucide="receipt" class="w-4 h-4 text-amber-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-900 font-heading">{{ $stats['countManualPending'] ?? 0 }}</p>
            <span class="text-[10px] text-amber-700 font-semibold mt-1 block">Antrean Perlu Dicek</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Donasi Hari Ini</span>
                <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-blue-900 font-heading">
                Rp {{ number_format($stats['donasiHariIni'], 0, ',', '.') }}
            </p>
            <span class="text-[10px] text-slate-400 mt-1 block">Pergerakan 24 Jam Terakhir</span>
        </div>
    </div>

    @if(isset($manualQueue) && $manualQueue->count() > 0)
        <!-- Section Antrean Verifikasi Transfer Manual -->
        <div class="bg-amber-50/70 rounded-3xl border border-amber-300 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-extrabold text-base text-amber-950">Antrean Verifikasi Transfer Manual</h2>
                        <p class="text-xs text-amber-800">Terdapat {{ $manualQueue->count() }} donasi menunggu pengecekan mutasi bank dan bukti transfer fisik.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto bg-white rounded-2xl border border-amber-200">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-amber-100/60 text-amber-900 font-bold border-b border-amber-200 uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-4">Kode / Waktu</th>
                            <th class="py-3 px-4">Donatur</th>
                            <th class="py-3 px-4">Kausa</th>
                            <th class="py-3 px-4">Nominal</th>
                            <th class="py-3 px-4">Bukti Transfer</th>
                            <th class="py-3 px-4 text-right">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100">
                        @foreach($manualQueue as $item)
                            <tr class="hover:bg-amber-50/40">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                    {{ $item->pesanan_pembayaran }}
                                    <span class="block text-[10px] font-normal text-slate-400">{{ $item->created_at ? $item->created_at->diffForHumans() : '' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-800">{{ $item->anonim ? 'Hamba Allah (Anonim)' : ($item->nama_donatur ?? ($item->user->name ?? 'Donatur')) }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $item->email_donatur ?? '-' }}</p>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs truncate">
                                    {{ $item->kausa->judul ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-[#087F5B]">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($item->path_bukti_manual)
                                        <a href="{{ route('admin.donasi.bukti', $item) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-[#087F5B] border border-emerald-300 font-bold text-[11px] hover:bg-emerald-100">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">Belum ada file</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2" x-data="{ rejectOpen: false, rejectNote: '' }">
                                        <!-- Approve Form -->
                                        <form method="POST" action="{{ route('admin.donasi.approveManual', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui donasi ini dan memasukkannya ke dana terkumpul?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs flex items-center gap-1 shadow-xs transition-all active:scale-95">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Setujui
                                            </button>
                                        </form>

                                        <!-- Reject Trigger -->
                                        <button @click="rejectOpen = !rejectOpen" type="button" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs flex items-center gap-1 transition-all">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Tolak
                                        </button>

                                        <!-- Inline Reject Modal / Box -->
                                        <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                                            <div @click.outside="rejectOpen = false" class="bg-white rounded-2xl p-5 max-w-md w-full space-y-3 text-left">
                                                <h3 class="font-heading font-bold text-sm text-slate-900">Tolak Bukti Transfer Manual</h3>
                                                <p class="text-xs text-slate-500">Berikan catatan alasan penolakan yang jelas agar donatur dapat memperbaiki:</p>
                                                <form method="POST" action="{{ route('admin.donasi.rejectManual', $item) }}">
                                                    @csrf
                                                    <textarea name="catatan_verifikasi_manual" required minlength="5" rows="3" placeholder="Contoh: Nominal pada struk transfer tidak sesuai, atau rekening tujuan salah..." class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500/30"></textarea>
                                                    <div class="flex items-center justify-end gap-2 pt-2">
                                                        <button type="button" @click="rejectOpen = false" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                                                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs">Kirim Penolakan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.donasi') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Cari Donatur / Kode Transaksi</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Kode pembayaran, nama donatur, email..."
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white transition-all">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program Kausa</label>
                <select name="kausa_id" class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white text-slate-800 transition-all truncate">
                    <option value="">Semua Program Kausa</option>
                    @foreach ($kausaList as $k)
                        <option value="{{ $k->id }}" @selected(request('kausa_id') == $k->id)>{{ Str::limit($k->judul, 35) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white text-slate-800 transition-all">
                    <option value="">Semua Status</option>
                    <option value="success" @selected(request('status') === 'success')>Berhasil</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="failed" @selected(request('status') === 'failed')>Gagal</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['search', 'kausa_id', 'status']))
                    <a href="{{ route('admin.donasi') }}" class="py-2 px-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Mutasi Donasi -->
    <div class="bg-white rounded-3xl border border-[#D9E2DE] overflow-hidden shadow-xs">
        @if ($donasis->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F6F8F7] text-slate-700 font-bold border-b border-[#D9E2DE] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-6">ID / Referensi</th>
                            <th class="py-3.5 px-4">Donatur</th>
                            <th class="py-3.5 px-4">Program Kausa</th>
                            <th class="py-3.5 px-4">Nominal Donasi</th>
                            <th class="py-3.5 px-4">Metode &amp; Waktu</th>
                            <th class="py-3.5 px-6 text-right">Status Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($donasis as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- ID / Referensi -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="font-mono font-bold text-slate-900 text-xs">
                                        {{ $item->pesanan_pembayaran ?? ('DON-'.str_pad($item->id, 5, '0', STR_PAD_LEFT)) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">ID: #{{ $item->id }}</span>
                                </td>

                                <!-- Donatur -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if ($item->anonim)
                                        <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                            <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span>Hamba Allah (Anonim)</span>
                                        </div>
                                    @else
                                        <p class="font-bold text-slate-900">{{ $item->nama_donatur ?? $item->user->name ?? 'Donatur' }}</p>
                                    @endif
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $item->email_donatur ?? $item->user->email ?? '-' }}</p>
                                </td>

                                <!-- Program Kausa -->
                                <td class="py-4 px-4 max-w-xs">
                                    <p class="font-bold text-slate-900 line-clamp-1 leading-snug">
                                        {{ $item->kausa->judul ?? '-' }}
                                    </p>
                                    <span class="text-[11px] text-emerald-800 font-semibold block mt-0.5 truncate">
                                        {{ $item->kausa->instansi->nama ?? 'Instansi' }}
                                    </span>
                                </td>

                                <!-- Nominal Donasi -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="font-extrabold text-[#087F5B] text-sm">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </p>
                                </td>

                                <!-- Metode & Waktu -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="font-bold uppercase tracking-wider text-[11px] text-slate-700 block">
                                        {{ $item->metode_pembayaran ?? 'QRIS Pemkab' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">
                                        {{ $item->dibayar_pada ? $item->dibayar_pada->format('d M Y, H:i') : ($item->created_at ? $item->created_at->format('d M Y, H:i') : '-') }}
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    @if (in_array($item->status, ['success', 'berhasil'], true))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Berhasil
                                        </span>
                                    @elseif ($item->status === \App\Models\Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Verif Manual
                                        </span>
                                    @elseif ($item->status === \App\Models\Donasi::STATUS_DITOLAK_MANUAL)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Ditolak Manual
                                        </span>
                                    @elseif ($item->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-500"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $donasis->links() }}
            </div>
        @else
            <div class="text-center py-16 p-8">
                <i data-lucide="credit-card" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                <h3 class="font-bold text-slate-800 text-sm font-heading">Tidak Ditemukan Data Donasi</h3>
                <p class="text-xs text-slate-500 mt-1">Belum ada donasi tercatat atau sesuaikan filter pencarian.</p>
            </div>
        @endif
    </div>

</div>
@endsection
