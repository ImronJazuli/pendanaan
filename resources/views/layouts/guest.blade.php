<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SI-PEDULI Tulungagung') }} - Masuk / Autentikasi</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans text-text-primary bg-background antialiased flex flex-col justify-between selection:bg-primary-subtle selection:text-primary relative overflow-x-hidden">

    <!-- Ambient Glow Background Decoration -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-primary/10 rounded-full blur-3xl opacity-70"></div>
        <div class="absolute bottom-0 right-0 w-[450px] h-[350px] bg-emerald-500/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Top Gov Bar -->
    <div class="bg-secondary text-white text-xs px-4 py-2 border-b border-secondary-hover/40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    PORTAL RESMI
                </span>
                <span class="text-emerald-100/90 hidden sm:inline">Pemerintah Kabupaten Tulungagung</span>
                <span class="text-white font-medium sm:hidden">Pemkab Tulungagung</span>
            </div>
            <a href="{{ route('landing') }}" class="text-emerald-200/80 hover:text-white inline-flex items-center gap-1.5 text-[11px] transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Main Content Container -->
    <main class="flex-1 flex flex-col justify-center items-center px-4 sm:px-6 py-10 sm:py-16">
        <div class="w-full sm:max-w-md">
            
            <!-- Brand Header -->
            <div class="text-center mb-8">
                <a href="{{ route('landing') }}" class="inline-flex flex-col items-center group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white shadow-lg shadow-primary/20 group-hover:scale-105 transition-transform mb-3.5 ring-4 ring-primary-subtle">
                        <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <h1 class="font-display font-bold text-xl sm:text-2xl text-text-primary tracking-tight">SI-PEDULI Tulungagung</h1>
                    <p class="text-xs text-text-secondary mt-1 max-w-xs">Sistem Informasi Penggalangan Dana & Penyaluran Bantuan Sosial Terintegrasi</p>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="bg-surface border border-border rounded-2xl shadow-card p-6 sm:p-8 backdrop-blur-sm">
                {{ $slot }}
            </div>

            <!-- Security Assurance Note -->
            <div class="mt-6 flex items-center justify-center gap-2 text-[11px] text-text-muted">
                <i data-lucide="lock" class="w-3.5 h-3.5 text-primary"></i>
                <span>Enkripsi SSL &bull; Portal Pengawasan Dinsos & PPID Pemkab</span>
            </div>

        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="py-4 text-center text-xs text-text-muted border-t border-border bg-surface/50">
        <p>&copy; {{ date('Y') }} Pemerintah Kabupaten Tulungagung. Hak Cipta Dilindungi.</p>
    </footer>

    <!-- Lucide init -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
