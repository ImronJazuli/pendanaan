@extends('layouts.guest')

@section('content')
<div class="h-[100dvh] flex items-center justify-center bg-tulungagung-bg p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <!-- Icon -->
            <div class="flex justify-center">
                <div class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center">
                    <i data-lucide="mail-check" class="w-12 h-12 text-primary"></i>
                </div>
            </div>

            <!-- Judul -->
            <div class="text-center">
                <h1 class="font-display font-bold text-2xl text-tulungagung-text mb-2">
                    Verifikasi Email Anda
                </h1>
                <p class="text-tulungagung-muted text-sm">
                    Kami telah mengirimkan tautan verifikasi ke alamat email Anda.
                    Silakan cek inbox atau folder spam.
                </p>
            </div>

            <!-- Flash Message -->
            @if (session('status'))
                <div class="rounded-2xl bg-primary/10 border border-primary/20 p-4 text-sm text-primary">
                    <div class="flex gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="space-y-3">
                @php
                    $resendRoute = null;
                    if (Auth::check()) {
                        $resendRoute = Auth::user()->role === 'instansi' 
                            ? route('instansi.verification.resend') 
                            : route('donatur.verification.resend');
                    }
                @endphp

                @if($resendRoute)
                    <form method="POST" action="{{ $resendRoute }}">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl bg-primary py-3 px-6 font-medium text-white hover:bg-primary-hover transition-colors">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                @endif

                @php
                    $logoutRoute = null;
                    if (Auth::check()) {
                        $logoutRoute = Auth::user()->role === 'instansi' 
                            ? route('instansi.logout') 
                            : route('donatur.logout');
                    }
                @endphp

                @if($logoutRoute)
                    <form method="POST" action="{{ $logoutRoute }}">
                        @csrf
                        <button type="submit" class="text-tulungagung-muted text-sm underline hover:text-tulungagung-text transition-colors">
                            Keluar dan gunakan akun lain
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
@endsection
