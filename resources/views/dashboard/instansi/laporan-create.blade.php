@extends('layouts.dashboard-instansi')

@section('title', 'Buat Laporan Pertanggungjawaban (LPJ) Penggunaan Dana')
@section('topbar-title', 'Penyusunan Laporan Penggunaan Dana')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
<div class="bg-[#F6F8F7] py-8" x-data="laporanForm()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#73817C] mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <a href="{{ route('instansi.laporan') }}" class="hover:text-[#087F5B] transition-colors">Laporan Dana</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[#17211E] font-medium">Buat Baru</span>
                </nav>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#17211E] tracking-tight">
                    Formulir Laporan Pertanggungjawaban (LPJ)
                </h1>
                <p class="text-xs sm:text-sm text-[#52615C] mt-1 max-w-3xl leading-relaxed">
                    Sampaikan rincian riil setiap pos pengeluaran dana bantuan beserta lampiran kuitansi/nota belanja otentik dan Berita Acara Serah Terima (BAST).
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('instansi.laporan') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                    <span>Terdapat kesalahan pengisian formulir:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('instansi.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- SECTION 1: Informasi Pokok LPJ -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-5">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">Informasi Umum Laporan</h2>
                        <p class="text-xs text-[#52615C]">Identitas kegiatan dan rentang waktu realisasi belanja.</p>
                    </div>
                </div>

                <!-- Kausa Yang Dilaporkan -->
                <div>
                    <label for="kausa_id" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Pilih Kausa Sosial Yang Dilaporkan <span class="text-rose-500">*</span>
                    </label>
                    <select id="kausa_id" name="kausa_id" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        <option value="">-- Pilih Kausa Sosial --</option>
                        @foreach($kausaList as $k)
                            <option value="{{ $k->id }}" {{ (string) old('kausa_id', $selectedKausaId) === (string) $k->id ? 'selected' : '' }}>
                                {{ $k->judul }} (Terkumpul: Rp {{ number_format($k->total_terkumpul, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('kausa_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Judul Laporan -->
                <div>
                    <label for="judul" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Judul Laporan Penggunaan Dana <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" required value="{{ old('judul') }}"
                           placeholder="Contoh: LPJ Penyaluran Bantuan Tahap I Bencana Longsor Sendang"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                    @error('judul') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Rentang Periode -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="periode_mulai" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Tanggal Mulai Realisasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="periode_mulai" name="periode_mulai" required value="{{ old('periode_mulai') }}"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        @error('periode_mulai') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="periode_selesai" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Tanggal Selesai Realisasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="periode_selesai" name="periode_selesai" required value="{{ old('periode_selesai') }}"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        @error('periode_selesai') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Ringkasan -->
                <div>
                    <label for="ringkasan" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Ringkasan / Latar Belakang Penyaluran
                    </label>
                    <textarea id="ringkasan" name="ringkasan" rows="3" maxlength="1000"
                              placeholder="Uraikan ringkasan penyaluran, jumlah warga/keluarga penerima manfaat, serta hasil yang dicapai..."
                              class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">{{ old('ringkasan') }}</textarea>
                    @error('ringkasan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- SECTION 2: Rincian Pengeluaran Belanja Dinamis (Itemized Expenses) -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                            <i data-lucide="calculator" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <h2 class="font-heading font-bold text-base text-[#123B32]">Rincian Pos Pengeluaran Belanja</h2>
                            <p class="text-xs text-[#52615C]">Tambahkan setiap nota belanja, tanggal pengeluaran, penerima, dan upload bukti kuitansi.</p>
                        </div>
                    </div>

                    <button type="button" @click="addRow()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-[#087F5B] text-xs font-bold transition-all border border-emerald-200">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Tambah Baris Belanja</span>
                    </button>
                </div>

                <!-- Tabel Input Dinamis -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 min-w-[200px]">Uraian Belanja <span class="text-rose-500">*</span></th>
                                <th class="py-2.5 px-3 min-w-[140px]">Nominal (Rp) <span class="text-rose-500">*</span></th>
                                <th class="py-2.5 px-3 min-w-[130px]">Tanggal Belanja</th>
                                <th class="py-2.5 px-3 min-w-[150px]">Penerima Manfaat</th>
                                <th class="py-2.5 px-3 min-w-[180px]">Bukti Nota/Kuitansi</th>
                                <th class="py-2.5 px-3 w-10 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="hover:bg-slate-50/50">
                                    <!-- Uraian -->
                                    <td class="py-2.5 px-3 align-top">
                                        <input type="text" :name="`rincian[${index}][uraian]`" x-model="row.uraian" required
                                               placeholder="Contoh: Beras 500kg @ Rp 14.000"
                                               class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#087F5B]">
                                    </td>

                                    <!-- Nominal -->
                                    <td class="py-2.5 px-3 align-top">
                                        <input type="number" :name="`rincian[${index}][nominal]`" x-model.number="row.nominal" required min="1"
                                               placeholder="7000000"
                                               class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 font-semibold focus:outline-none focus:ring-1 focus:ring-[#087F5B]">
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="py-2.5 px-3 align-top">
                                        <input type="date" :name="`rincian[${index}][tanggal_pengeluaran]`" x-model="row.tanggal"
                                               class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#087F5B]">
                                    </td>

                                    <!-- Penerima Manfaat -->
                                    <td class="py-2.5 px-3 align-top">
                                        <input type="text" :name="`rincian[${index}][penerima_manfaat]`" x-model="row.penerima"
                                               placeholder="50 KK Warga RT 02"
                                               class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#087F5B]">
                                    </td>

                                    <!-- Upload Bukti -->
                                    <td class="py-2.5 px-3 align-top">
                                        <input type="file" :name="`rincian[${index}][bukti]`" accept=".pdf,.jpg,.jpeg,.png"
                                               class="w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer border border-slate-200 rounded-lg p-1 bg-white">
                                    </td>

                                    <!-- Hapus Baris -->
                                    <td class="py-2.5 px-3 align-middle text-center">
                                        <button type="button" @click="removeRow(index)" x-show="rows.length > 1"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Baris">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50/80 border-t border-slate-200 font-bold text-xs">
                            <tr>
                                <td class="py-3 px-3 text-right text-slate-700">Total Akumulasi Realisasi Belanja:</td>
                                <td class="py-3 px-3 text-emerald-700 text-sm font-extrabold" x-text="formatRupiah(totalPengeluaran)"></td>
                                <td colspan="4" class="py-3 px-3 text-slate-400 text-[11px] font-normal">
                                    *Pastikan seluruh nota fisik tersimpan untuk keperluan uji petik audit Pemkab.
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <div class="text-xs text-[#73817C]">
                    Laporan yang dikirim akan diverifikasi oleh Admin Pemkab Tulungagung sebelum tayang di portal transparansi.
                </div>

                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button type="submit" name="action" value="draft"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all active:scale-95">
                        <i data-lucide="save" class="w-4 h-4 text-slate-500"></i>
                        <span>Simpan Draf</span>
                    </button>

                    <button type="submit" name="action" value="submit"
                            onclick="return confirm('Apakah Anda yakin laporan dan seluruh rincian belanja sudah lengkap dan siap dikirim ke Admin Pemkab?')"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-sm transition-all active:scale-95">
                        <i data-lucide="send" class="w-4 h-4 text-white"></i>
                        <span>Kirim Laporan (LPJ)</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
function laporanForm() {
    return {
        rows: [
            { uraian: '', nominal: 0, tanggal: '', penerima: '' }
        ],
        addRow() {
            this.rows.push({ uraian: '', nominal: 0, tanggal: '', penerima: '' });
            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
        },
        removeRow(index) {
            if (this.rows.length > 1) {
                this.rows.splice(index, 1);
            }
        },
        get totalPengeluaran() {
            return this.rows.reduce((sum, r) => sum + (Number(r.nominal) || 0), 0);
        },
        formatRupiah(number) {
            return 'Rp ' + Number(number).toLocaleString('id-ID');
        }
    }
}
</script>
@endsection
