<!-- ==================== FOOTER PEMKAB TULUNGAGUNG ==================== -->
<footer class="bg-white border-t border-[#D9E2DE] mt-16 text-slate-600 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Col 1: Identitas -->
            <div class="space-y-3 md:col-span-1">
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-[#087F5B] flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                        <i data-lucide="landmark" class="w-4 h-4"></i>
                    </div>
                    <span class="font-heading font-bold text-sm text-[#123B32]">SI-PEDULI TULUNGAGUNG</span>
                </a>
                <p class="text-[12px] text-[#73817C] leading-relaxed">
                    Portal resmi penggalangan dana sosial kemasyarakatan dan kedaruratan di bawah naungan Pemerintah Kabupaten Tulungagung.
                </p>
                <div class="flex items-center gap-2 pt-1 text-slate-400">
                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-semibold text-[10px] border border-emerald-200">Terverifikasi</span>
                    <span class="text-[11px]">&bull; Terintegrasi PPID &amp; Dinsos</span>
                </div>
            </div>

            <!-- Col 2: Kategori Kausa -->
            <div class="space-y-2">
                <p class="font-bold text-[#17211E] text-xs uppercase tracking-wider">Kategori Program</p>
                <ul class="space-y-1.5 text-xs text-[#52615C]">
                    <li><a href="{{ route('kausa.index') }}?kategori=1" class="hover:text-[#087F5B] transition-colors">Bencana Alam</a></li>
                    <li><a href="{{ route('kausa.index') }}?kategori=2" class="hover:text-[#087F5B] transition-colors">Panti Asuhan &amp; LKSA</a></li>
                    <li><a href="{{ route('kausa.index') }}?kategori=3" class="hover:text-[#087F5B] transition-colors">Tempat Ibadah</a></li>
                    <li><a href="{{ route('kausa.index') }}?kategori=4" class="hover:text-[#087F5B] transition-colors">Lansia &amp; Dhuafa</a></li>
                </ul>
            </div>

            <!-- Col 3: Regulasi & Integritas -->
            <div class="space-y-2">
                <p class="font-bold text-[#17211E] text-xs uppercase tracking-wider">Regulasi &amp; Integritas</p>
                <ul class="space-y-1.5 text-xs text-[#52615C]">
                    <li><a href="{{ route('pages.tentang') }}" class="hover:text-[#087F5B] transition-colors">Tentang Portal</a></li>
                    <li><a href="{{ route('pages.faq') }}" class="hover:text-[#087F5B] transition-colors">Tanya Jawab (FAQ)</a></li>
                    <li><a href="{{ route('pages.syarat') }}" class="hover:text-[#087F5B] transition-colors">Syarat &amp; Ketentuan Pengajuan</a></li>
                    <li><a href="{{ route('pages.privasi') }}" class="hover:text-[#087F5B] transition-colors">Kebijakan Privasi Donatur</a></li>
                </ul>
            </div>

            <!-- Col 4: Kontak Pengelola -->
            <div class="space-y-2">
                <p class="font-bold text-[#17211E] text-xs uppercase tracking-wider">Sekretariat Verifikator</p>
                <p class="text-xs text-[#52615C] leading-relaxed">
                    Gedung Dinas Komunikasi dan Informatika &bull; Dinsos Tulungagung<br>
                    Jl. RA Kartini No. 01, Kepatihan, Kec. Tulungagung, Jawa Timur 66212
                </p>
                <p class="text-xs font-semibold text-emerald-800">Email: ppid@tulungagung.go.id</p>
                <p class="text-[11px] text-slate-500">Layanan Call Center 112 / WhatsApp Dinsos</p>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-slate-500 text-[11px]">
            <p>&copy; {{ date('Y') }} Pemerintah Kabupaten Tulungagung. Hak Cipta Dilindungi Undang-Undang.</p>
            <p class="text-slate-400">Portal Penggalangan Bantuan Sosial Terakreditasi &amp; Amanah</p>
        </div>
    </div>
</footer>
