@extends('layouts.dashboard-admin')

@section('title', 'Verifikasi Legalitas OPD & Ormas')

@section('content')
<div class="space-y-6" x-data="adminLegalitas()" x-cloak>

    <!-- Top Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1.5" aria-label="Breadcrumb">
                <a href="{{ route('dashboard.admin') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Admin</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <span class="text-slate-800 font-semibold">Verifikasi Legalitas Entitas</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#123B32] tracking-tight">
                Legalitas OPD, Lembaga Sosial &amp; Yayasan
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-3xl">
                Kurasi dan verifikasi keabsahan dokumen SK Kemenkumham, NPWP, serta surat rekomendasi kedinasan bagi seluruh instansi pengaju kausa di Kabupaten Tulungagung.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-300">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                Verifikasi Standar Pemkab
            </span>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session('status'))
        <div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-xs sm:text-sm text-emerald-900 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-300 bg-red-50 p-4 text-xs text-red-900 space-y-1 shadow-2xs">
            <div class="flex items-center gap-2 font-bold">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                <span>Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Terdaftar</span>
                <i data-lucide="building-2" class="w-4 h-4 text-[#087F5B]"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-500 mt-1 block">OPD &amp; Lembaga Masyarakat</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Menunggu Uji</span>
                <i data-lucide="clock" class="w-4 h-4 text-amber-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-900 font-heading">{{ $stats['belum_diverifikasi'] }}</p>
            <span class="text-[10px] text-amber-700 font-semibold mt-1 block">Perlu Pemeriksaan Berkas</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Terverifikasi</span>
                <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#087F5B] font-heading">{{ $stats['terverifikasi'] }}</p>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 block">Sah Mengajukan Kausa</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Ditolak</span>
                <i data-lucide="x-circle" class="w-4 h-4 text-rose-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-rose-900 font-heading">{{ $stats['ditolak'] }}</p>
            <span class="text-[10px] text-rose-700 font-semibold mt-1 block">Berkas Tidak Memenuhi Syarat</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.legalitas') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Cari Instansi / Penanggung Jawab</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama dinas, yayasan, no. registrasi, email..."
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white transition-all">
                </div>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Verifikasi</label>
                <select name="status" class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white text-slate-800 transition-all">
                    <option value="">Semua Status</option>
                    <option value="belum_diverifikasi" @selected(request('status') === 'belum_diverifikasi')>Menunggu Verifikasi (Pending)</option>
                    <option value="terverifikasi" @selected(request('status') === 'terverifikasi')>Terverifikasi Sah</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.legalitas') }}" class="py-2 px-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Instansi -->
    <div class="bg-white rounded-3xl border border-[#D9E2DE] overflow-hidden shadow-xs">
        @if ($instansis->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F6F8F7] text-slate-700 font-bold border-b border-[#D9E2DE] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-6">Instansi / Lembaga</th>
                            <th class="py-3.5 px-4">Kontak &amp; Penanggung Jawab</th>
                            <th class="py-3.5 px-4">Berkas Pendukung</th>
                            <th class="py-3.5 px-4">Status Legalitas</th>
                            <th class="py-3.5 px-6 text-right">Tindakan Kurasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($instansis as $instansi)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Instansi Info -->
                                <td class="py-4 px-6 max-w-xs">
                                    <div class="flex items-start gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5 border border-emerald-200">
                                            <i data-lucide="building" class="w-4 h-4"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 text-sm leading-snug truncate">{{ $instansi->nama }}</p>
                                            <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                                <span class="font-semibold text-emerald-800 uppercase">{{ $instansi->jenis ?? 'Yayasan' }}</span>
                                                <span>&bull;</span>
                                                <span class="font-mono text-slate-500">{{ $instansi->nomor_registrasi ?? 'No Reg: -' }}</span>
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $instansi->alamat ?? 'Tulungagung' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kontak & PIC -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="font-semibold text-slate-800 text-xs">{{ $instansi->user->name ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $instansi->user->email ?? '-' }}</p>
                                    <p class="text-[11px] text-emerald-700 font-mono">{{ $instansi->nomor_telepon ?? $instansi->user->phone_number ?? '-' }}</p>
                                </td>

                                <!-- Berkas Legalitas -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @php $dokCount = $instansi->dokumen->count(); @endphp
                                    @if ($dokCount > 0)
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">
                                                <i data-lucide="paperclip" class="w-3 h-3 text-slate-500"></i>
                                                {{ $dokCount }} Berkas Terunggah
                                            </span>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($instansi->dokumen->take(2) as $dok)
                                                    @if ($dok->path_file)
                                                        <a href="{{ asset('storage/' . $dok->path_file) }}" target="_blank" class="text-[10px] text-[#087F5B] hover:underline flex items-center gap-0.5" title="{{ $dok->nama_file }}">
                                                            <i data-lucide="file" class="w-2.5 h-2.5"></i>
                                                            <span class="truncate max-w-[100px]">{{ $dok->nama_file }}</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Belum unggah berkas</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if ($instansi->status_verifikasi === 'terverifikasi')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <i data-lucide="badge-check" class="w-3.5 h-3.5 text-emerald-600"></i> Terverifikasi
                                        </span>
                                    @elseif ($instansi->status_verifikasi === 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Menunggu Uji
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($instansi->status_verifikasi !== 'terverifikasi')
                                            <!-- Tombol Setujui -->
                                            <button type="button"
                                                    @click="openVerifyModal({{ $instansi->id }}, '{{ addslashes($instansi->nama) }}')"
                                                    class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-[#087F5B] font-bold text-xs transition-colors flex items-center gap-1"
                                                    title="Setujui Legalitas">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>Verifikasi</span>
                                            </button>

                                            <!-- Tombol Tolak -->
                                            <button type="button"
                                                    @click="openRejectModal({{ $instansi->id }}, '{{ addslashes($instansi->nama) }}')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors"
                                                    title="Tolak Legalitas">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @else
                                            <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1">
                                                <i data-lucide="lock" class="w-3 h-3"></i> Tervalidasi
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $instansis->links() }}
            </div>
        @else
            <div class="text-center py-16 p-8">
                <i data-lucide="building" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                <h3 class="font-bold text-slate-800 text-sm font-heading">Tidak Ditemukan Data Instansi</h3>
                <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau filter status verifikasi.</p>
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Verifikasi Instansi -->
    <div x-show="showVerifyModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-xl border border-slate-200"
             @click.outside="showVerifyModal = false">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-[#087F5B] flex items-center justify-center mx-auto">
                <i data-lucide="shield-check" class="w-7 h-7"></i>
            </div>
            <div>
                <h3 class="font-display font-bold text-lg text-slate-900">Verifikasi Resmi Instansi?</h3>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                    Anda akan menyatakan berkas legalitas dari <strong class="text-slate-900" x-text="targetNama"></strong> sah menurut standar Pemkab Tulungagung. Instansi ini akan dapat mengajukan kausa pendanaan sosial.
                </p>
            </div>
            <form :action="'/dashboard/admin/legalitas/' + targetId + '/verify'" method="POST" class="pt-2">
                @csrf
                <div class="flex items-center gap-3">
                    <button type="button"
                            @click="showVerifyModal = false"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-xs font-bold text-white shadow-xs transition-colors">
                        Ya, Verifikasi Sah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Penolakan Legalitas Instansi -->
    <div x-show="showRejectModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-200 text-left"
             @click.outside="showRejectModal = false">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-base text-slate-900">Tolak Legalitas Instansi</h3>
                    <p class="text-xs text-slate-500" x-text="targetNama"></p>
                </div>
            </div>

            <form :action="'/dashboard/admin/legalitas/' + targetId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_penolakan"
                              rows="3"
                              required
                              placeholder="Jelaskan kekurangan berkas (misal: SK Kemenkumham belum dilegalisir, NPWP tidak terbaca, dll)..."
                              class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 p-3 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 transition-all"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="button"
                            @click="showRejectModal = false"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-xs font-bold text-white shadow-xs transition-colors">
                        Tolak Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminLegalitas() {
    return {
        showVerifyModal: false,
        showRejectModal: false,
        targetId: null,
        targetNama: '',

        openVerifyModal(id, nama) {
            this.targetId = id;
            this.targetNama = nama;
            this.showVerifyModal = true;
        },

        openRejectModal(id, nama) {
            this.targetId = id;
            this.targetNama = nama;
            this.showRejectModal = true;
        }
    }
}
</script>
@endpush
@endsection
