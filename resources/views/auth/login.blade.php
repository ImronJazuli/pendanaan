@extends('layouts.guest')

@section('styles')
<style>
@keyframes fadeSlideIn {
    from { opacity: 0; filter: blur(4px); transform: translateY(16px); }
    to   { opacity: 1; filter: blur(0);   transform: translateY(0); }
}
@keyframes slideRightIn {
    from { opacity: 0; filter: blur(8px); transform: translateX(24px); }
    to   { opacity: 1; filter: blur(0);   transform: translateX(0); }
}
.animate-element     { opacity: 0; animation: fadeSlideIn  0.8s cubic-bezier(0.16,1,0.3,1) forwards; }
.animate-slide-right { opacity: 0; animation: slideRightIn 1.0s cubic-bezier(0.16,1,0.3,1) forwards; }
.animate-delay-100  { animation-delay: 100ms; }
.animate-delay-200  { animation-delay: 200ms; }
.animate-delay-300  { animation-delay: 300ms; }
.animate-delay-400  { animation-delay: 400ms; }
.animate-delay-500  { animation-delay: 500ms; }
.animate-delay-600  { animation-delay: 600ms; }
</style>
@endsection

@php
    $tab  = request('tab',  'donatur');
    $mode = request('mode', 'signin');
@endphp

@section('content')
<div class="h-[100dvh] flex flex-col md:flex-row overflow-x-hidden">

  <!-- KOLOM KIRI -->
  <div class="flex-1 bg-tulungagung-bg flex items-center justify-center p-8 overflow-y-auto">
    <div class="max-w-md w-full flex flex-col gap-6 py-8">

      <!-- [1] Logo + Judul -->
      <div class="animate-element animate-delay-100 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary to-primary-hover mb-4">
          <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
        </div>
        <h1 class="font-display font-bold text-2xl text-tulungagung-text">SI-PEDULI Tulungagung</h1>
        <p class="text-sm text-tulungagung-muted mt-1">Platform Donasi Resmi Pemkab Tulungagung</p>
      </div>

      <!-- [2] Tab Switcher -->
      <div class="animate-element animate-delay-200">
        <div class="flex border-b border-tulungagung-border">
          <button type="button"
            class="tab-btn flex-1 py-3 text-sm font-medium transition-colors {{$tab === 'donatur' ? 'border-b-2 border-primary text-primary' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-tab="donatur">
            Donatur
          </button>
          <button type="button"
            class="tab-btn flex-1 py-3 text-sm font-medium transition-colors {{$tab === 'instansi' ? 'border-b-2 border-primary text-primary' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-tab="instansi">
            Instansi
          </button>
          <button type="button"
            class="tab-btn flex-1 py-3 text-sm font-medium transition-colors {{$tab === 'admin' ? 'border-b-2 border-primary text-primary' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-tab="admin">
            Admin
          </button>
        </div>
      </div>

      <!-- [3] Flash Messages -->
      @if(request('status'))
        <div class="animate-element animate-delay-300">
          @if(request('status') === 'verify-email')
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">
              <div class="flex gap-3">
                <i data-lucide="mail" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>Email verifikasi telah dikirim. Silakan cek inbox Anda.</div>
              </div>
            </div>
          @elseif(request('status') === 'verified')
            <div class="rounded-2xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
              <div class="flex gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>Email berhasil diverifikasi! Silakan login.</div>
              </div>
            </div>
          @elseif(request('status') === 'need-verify')
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">
              <div class="flex gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>Email Anda belum diverifikasi. Silakan cek inbox Anda.</div>
              </div>
            </div>
          @elseif(request('status') === 'pending-admin')
            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-4 text-sm text-blue-800">
              <div class="flex gap-3">
                <i data-lucide="clock" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>Pendaftaran berhasil! Akun Anda akan diverifikasi oleh admin.</div>
              </div>
            </div>
          @elseif(request('status') === 'google-failed')
            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
              <div class="flex gap-3">
                <i data-lucide="x-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>Login Google gagal. Silakan coba lagi.</div>
              </div>
            </div>
          @elseif(request('status') === 'already-verified')
            <div class="rounded-2xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
              <div class="flex gap-3">
                <i data-lucide="info" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>Email Anda sudah diverifikasi sebelumnya.</div>
              </div>
            </div>
          @endif
        </div>
      @endif

      @if($errors->any())
        <div class="animate-element animate-delay-300 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
          <div class="flex gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <div>
              <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                  <li>{{$error}}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      <!-- [4] Panel Donatur -->
      <div id="panel-donatur" class="{{$tab !== 'donatur' ? 'hidden' : ''}}">
        <!-- Mode Switcher -->
        <div class="flex gap-2 p-1.5 bg-surface-muted rounded-2xl mb-6">
          <button type="button"
            class="mode-btn-donatur flex-1 py-2.5 px-4 text-sm font-medium rounded-xl transition-all {{$mode === 'signin' ? 'bg-primary text-white shadow-sm' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-mode="signin">
            Masuk
          </button>
          <button type="button"
            class="mode-btn-donatur flex-1 py-2.5 px-4 text-sm font-medium rounded-xl transition-all {{$mode === 'register' ? 'bg-primary text-white shadow-sm' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-mode="register">
            Daftar
          </button>
        </div>

        <!-- Form Sign In Donatur -->
        <div id="donatur-signin" class="{{$mode !== 'signin' ? 'hidden' : ''}} space-y-4">
          <form method="POST" action="{{route('donatur.login')}}" class="space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Email</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="email" name="email" required autocomplete="email"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="nama@email.com" value="{{old('email')}}">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="donatur-signin-password" name="password" required autocomplete="current-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;">
                <button type="button" onclick="togglePassword('donatur-signin-password', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-tulungagung-border accent-primary">
                <span class="text-sm text-tulungagung-muted">Ingat saya</span>
              </label>
            </div>
            <button type="submit"
              class="w-full rounded-2xl bg-primary py-4 font-medium text-white hover:bg-primary-hover transition-colors">
              Masuk
            </button>
          </form>

          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-tulungagung-border"></div>
            </div>
            <div class="relative flex justify-center text-xs">
              <span class="bg-tulungagung-bg px-3 text-tulungagung-muted">Atau masuk dengan</span>
            </div>
          </div>

          <a href="{{route('donatur.google')}}"
            class="w-full flex items-center justify-center gap-3 border border-tulungagung-border rounded-2xl py-3.5 hover:bg-surface-muted transition-colors text-sm font-medium text-tulungagung-text">
            <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Masuk dengan Google
          </a>
        </div>

        <!-- Form Register Donatur -->
        <div id="donatur-register" class="{{$mode !== 'register' ? 'hidden' : ''}} space-y-4">
          <form method="POST" action="{{route('donatur.register')}}" class="space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Nama Lengkap</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="text" name="name" required autocomplete="name"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="John Doe" value="{{old('name')}}">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Email</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="email" name="email" required autocomplete="email"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="nama@email.com" value="{{old('email')}}">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="donatur-register-password" name="password" required autocomplete="new-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="Min. 8 karakter">
                <button type="button" onclick="togglePassword('donatur-register-password', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Konfirmasi Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="donatur-register-password-confirm" name="password_confirmation" required autocomplete="new-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="Ulangi password">
                <button type="button" onclick="togglePassword('donatur-register-password-confirm', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <button type="submit"
              class="w-full rounded-2xl bg-primary py-4 font-medium text-white hover:bg-primary-hover transition-colors">
              Daftar Sekarang
            </button>
          </form>

          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-tulungagung-border"></div>
            </div>
            <div class="relative flex justify-center text-xs">
              <span class="bg-tulungagung-bg px-3 text-tulungagung-muted">Atau daftar dengan</span>
            </div>
          </div>

          <a href="{{route('donatur.google')}}"
            class="w-full flex items-center justify-center gap-3 border border-tulungagung-border rounded-2xl py-3.5 hover:bg-surface-muted transition-colors text-sm font-medium text-tulungagung-text">
            <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Daftar dengan Google
          </a>
        </div>
      </div>

      <!-- [5] Panel Instansi -->
      <div id="panel-instansi" class="{{$tab !== 'instansi' ? 'hidden' : ''}}">
        <!-- Mode Switcher -->
        <div class="flex gap-2 p-1.5 bg-surface-muted rounded-2xl mb-6">
          <button type="button"
            class="mode-btn-instansi flex-1 py-2.5 px-4 text-sm font-medium rounded-xl transition-all {{$mode === 'signin' ? 'bg-primary text-white shadow-sm' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-mode="signin">
            Masuk
          </button>
          <button type="button"
            class="mode-btn-instansi flex-1 py-2.5 px-4 text-sm font-medium rounded-xl transition-all {{$mode === 'register' ? 'bg-primary text-white shadow-sm' : 'text-tulungagung-muted hover:text-tulungagung-text'}}"
            data-mode="register">
            Daftar Instansi
          </button>
        </div>

        <!-- Form Sign In Instansi -->
        <div id="instansi-signin" class="{{$mode !== 'signin' ? 'hidden' : ''}} space-y-4">
          <form method="POST" action="{{route('instansi.login')}}" class="space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Email atau NPWP</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="text" name="login_id" required autocomplete="username"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="Email atau NPWP instansi" value="{{old('login_id')}}">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="instansi-signin-password" name="password" required autocomplete="current-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;">
                <button type="button" onclick="togglePassword('instansi-signin-password', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <div class="flex items-center">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-tulungagung-border accent-primary">
                <span class="text-sm text-tulungagung-muted">Ingat saya</span>
              </label>
            </div>
            <button type="submit"
              class="w-full rounded-2xl bg-primary py-4 font-medium text-white hover:bg-primary-hover transition-colors">
              Masuk
            </button>
          </form>
        </div>

        <!-- Form Register Instansi -->
        <div id="instansi-register" class="{{$mode !== 'register' ? 'hidden' : ''}} space-y-4">
          <form method="POST" action="{{route('instansi.register')}}" class="space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Metode Identitas</label>
              <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="identity_type" value="email" checked
                    class="w-4 h-4 accent-primary">
                  <span class="text-sm text-tulungagung-text">Email Instansi</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="identity_type" value="npwp"
                    class="w-4 h-4 accent-primary">
                  <span class="text-sm text-tulungagung-text">NPWP</span>
                </label>
              </div>
            </div>

            <div id="field-email-instansi">
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Email Instansi</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="email" name="email" autocomplete="email"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="instansi@tulungagung.go.id" value="{{old('email')}}">
              </div>
            </div>

            <div id="field-npwp-instansi" class="hidden">
              <label class="block text-sm font-medium text-tulungagung-text mb-2">NPWP Instansi</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="text" id="npwp-input" name="npwp" maxlength="20"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text font-mono"
                  placeholder="00.000.000.0-000.000" value="{{old('npwp')}}">
              </div>
              <p class="text-xs text-tulungagung-muted mt-1">Format: XX.XXX.XXX.X-XXX.XXX (15 digit)</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Nama Instansi</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="text" name="name" required autocomplete="organization"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="Nama lengkap instansi" value="{{old('name')}}">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Jenis Instansi</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <select name="jenis" required
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none text-tulungagung-text appearance-none">
                  <option value="">Pilih jenis instansi</option>
                  <option value="opd" {{old('jenis') === 'opd' ? 'selected' : ''}}>OPD (Organisasi Perangkat Daerah)</option>
                  <option value="lembaga_sosial" {{old('jenis') === 'lembaga_sosial' ? 'selected' : ''}}>Lembaga Sosial</option>
                  <option value="ormas" {{old('jenis') === 'ormas' ? 'selected' : ''}}>Ormas</option>
                  <option value="yayasan" {{old('jenis') === 'yayasan' ? 'selected' : ''}}>Yayasan</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Nomor Telepon</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="tel" name="phone_number" required autocomplete="tel"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="08xxxxxxxxxx" value="{{old('phone_number')}}">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="instansi-register-password" name="password" required autocomplete="new-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="Min. 8 karakter">
                <button type="button" onclick="togglePassword('instansi-register-password', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Konfirmasi Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="instansi-register-password-confirm" name="password_confirmation" required autocomplete="new-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="Ulangi password">
                <button type="button" onclick="togglePassword('instansi-register-password-confirm', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>

            <div class="rounded-xl bg-blue-50 border border-blue-200 p-3 text-xs text-blue-800">
              <div class="flex gap-2">
                <i data-lucide="info" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                <div>Akun instansi akan diverifikasi oleh tim admin Pemkab Tulungagung.</div>
              </div>
            </div>

            <button type="submit"
              class="w-full rounded-2xl bg-primary py-4 font-medium text-white hover:bg-primary-hover transition-colors">
              Daftar Instansi
            </button>
          </form>
        </div>
      </div>

      <!-- [6] Panel Admin -->
      <div id="panel-admin" class="{{$tab !== 'admin' ? 'hidden' : ''}}">
        <div class="space-y-4">
          <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">
            <div class="flex gap-3">
              <i data-lucide="shield-alert" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
              <div>
                <p class="font-medium mb-1">Akses Terbatas</p>
                <p>Hanya untuk pengelola internal Pemkab Tulungagung.</p>
              </div>
            </div>
          </div>

          <form method="POST" action="{{route('admin.login')}}" class="space-y-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Email Admin</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5">
                <input type="email" name="email" required autocomplete="email"
                  class="w-full bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="admin@tulungagung.go.id" value="{{old('email')}}">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-tulungagung-text mb-2">Password</label>
              <div class="rounded-2xl border border-tulungagung-border bg-white/60 backdrop-blur-sm transition-colors focus-within:border-primary/60 focus-within:bg-primary/5 flex items-center pr-4">
                <input type="password" id="admin-password" name="password" required autocomplete="current-password"
                  class="flex-1 bg-transparent text-sm p-4 rounded-2xl focus:outline-none placeholder:text-tulungagung-muted/50 text-tulungagung-text"
                  placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;">
                <button type="button" onclick="togglePassword('admin-password', this)"
                  class="text-tulungagung-muted hover:text-tulungagung-text transition-colors flex-shrink-0">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <div class="flex items-center">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-tulungagung-border accent-primary">
                <span class="text-sm text-tulungagung-muted">Ingat saya</span>
              </label>
            </div>
            <button type="submit"
              class="w-full rounded-2xl bg-primary py-4 font-medium text-white hover:bg-primary-hover transition-colors">
              Masuk sebagai Admin
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>

  <!-- KOLOM KANAN -->
  <div class="hidden md:block flex-1 p-4">
    <div class="relative h-full rounded-3xl overflow-hidden">
      <!-- Background image -->
      <div class="absolute inset-0 bg-gradient-to-br from-secondary to-primary">
        <img src="/images/hero-login.jpg" alt="Portal Donasi Tulungagung"
          class="w-full h-full object-cover mix-blend-overlay opacity-40"
          onerror="this.style.display='none'">
      </div>
      <!-- Decorative circles -->
      <div class="absolute top-8 right-8 w-32 h-32 rounded-full bg-white/5 border border-white/10"></div>
      <div class="absolute top-20 right-20 w-16 h-16 rounded-full bg-white/5 border border-white/10"></div>
      <!-- Overlay gradient -->
      <div class="absolute inset-0 bg-gradient-to-t from-secondary/80 via-secondary/20 to-transparent pointer-events-none"></div>

      <!-- Headline -->
      <div class="absolute top-8 left-8 right-8 animate-element animate-delay-300">
        <p class="text-white/60 text-sm font-medium mb-2">Pemkab Tulungagung</p>
        <h2 class="font-display font-bold text-3xl text-white leading-tight">Bersama Wujudkan<br>Tulungagung Peduli</h2>
      </div>

      <!-- Floating stat cards -->
      <div class="absolute bottom-8 left-8 right-8 flex flex-col gap-4 animate-slide-right animate-delay-500">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-5 shadow-2xl">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
              <i data-lucide="heart-handshake" class="w-6 h-6 text-white"></i>
            </div>
            <div>
              <p class="text-white/70 text-sm">Kausa Aktif</p>
              <p class="text-white font-display font-bold text-2xl">127</p>
            </div>
          </div>
        </div>
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-5 shadow-2xl">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
              <i data-lucide="banknote" class="w-6 h-6 text-white"></i>
            </div>
            <div>
              <p class="text-white/70 text-sm">Dana Tersalurkan</p>
              <p class="text-white font-display font-bold text-2xl">Rp 2,4M</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Toggle password visibility.
     * @param {string} inputId - The id of the password input.
     * @param {HTMLButtonElement} btn - The toggle button element.
     */
    window.togglePassword = function (inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) { return; }
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        const icon = btn.querySelector('i[data-lucide]');
        if (icon) {
            icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');
            if (window.lucide) { window.lucide.createIcons(); }
        }
    };

    // ── URL state helpers ────────────────────────────────────────────────────

    /**
     * Read a query param from the current URL.
     * @param {string} key
     * @returns {string|null}
     */
    function getParam(key) {
        return new URLSearchParams(window.location.search).get(key);
    }

    /**
     * Update the URL without reloading by merging new params.
     * @param {Record<string, string>} params
     */
    function updateUrl(params) {
        const sp = new URLSearchParams(window.location.search);
        Object.entries(params).forEach(([k, v]) => sp.set(k, v));
        const newUrl = window.location.pathname + '?' + sp.toString();
        window.history.pushState({}, '', newUrl);
    }

    // ── Tab switcher ─────────────────────────────────────────────────────────

    const TABS = ['donatur', 'instansi', 'admin'];

    /**
     * Activate a top-level tab.
     * @param {string} tab
     */
    function activateTab(tab) {
        if (!TABS.includes(tab)) { return; }

        // Toggle panels
        TABS.forEach(function (t) {
            const panel = document.getElementById('panel-' + t);
            if (panel) {
                panel.classList.toggle('hidden', t !== tab);
            }
        });

        // Toggle tab button styles
        document.querySelectorAll('.tab-btn').forEach(function (btn) {
            const active = btn.dataset.tab === tab;
            btn.classList.toggle('border-b-2', active);
            btn.classList.toggle('border-primary', active);
            btn.classList.toggle('text-primary', active);
            btn.classList.toggle('text-tulungagung-muted', !active);
            btn.classList.toggle('hover:text-tulungagung-text', !active);
        });

        updateUrl({ tab: tab, mode: getParam('mode') || 'signin' });
    }

    // Bind tab buttons
    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activateTab(btn.dataset.tab);
        });
    });

    // ── Mode switcher (donatur) ───────────────────────────────────────────────

    /**
     * Switch donatur form between signin and register.
     * @param {string} mode  'signin' | 'register'
     */
    function activateDonaturMode(mode) {
        const signin   = document.getElementById('donatur-signin');
        const register = document.getElementById('donatur-register');
        if (!signin || !register) { return; }

        signin.classList.toggle('hidden', mode !== 'signin');
        register.classList.toggle('hidden', mode !== 'register');

        document.querySelectorAll('.mode-btn-donatur').forEach(function (btn) {
            const active = btn.dataset.mode === mode;
            btn.classList.toggle('bg-primary', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('shadow-sm', active);
            btn.classList.toggle('text-tulungagung-muted', !active);
            btn.classList.toggle('hover:text-tulungagung-text', !active);
        });

        updateUrl({ tab: getParam('tab') || 'donatur', mode: mode });
    }

    document.querySelectorAll('.mode-btn-donatur').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activateDonaturMode(btn.dataset.mode);
        });
    });

    // ── Mode switcher (instansi) ──────────────────────────────────────────────

    /**
     * Switch instansi form between signin and register.
     * @param {string} mode  'signin' | 'register'
     */
    function activateInstansiMode(mode) {
        const signin   = document.getElementById('instansi-signin');
        const register = document.getElementById('instansi-register');
        if (!signin || !register) { return; }

        signin.classList.toggle('hidden', mode !== 'signin');
        register.classList.toggle('hidden', mode !== 'register');

        document.querySelectorAll('.mode-btn-instansi').forEach(function (btn) {
            const active = btn.dataset.mode === mode;
            btn.classList.toggle('bg-primary', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('shadow-sm', active);
            btn.classList.toggle('text-tulungagung-muted', !active);
            btn.classList.toggle('hover:text-tulungagung-text', !active);
        });

        updateUrl({ tab: getParam('tab') || 'instansi', mode: mode });
    }

    document.querySelectorAll('.mode-btn-instansi').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activateInstansiMode(btn.dataset.mode);
        });
    });

    // ── Identity type toggle (instansi register) ──────────────────────────────

    const fieldEmail = document.getElementById('field-email-instansi');
    const fieldNpwp  = document.getElementById('field-npwp-instansi');
    const emailInput = fieldEmail ? fieldEmail.querySelector('input') : null;
    const npwpInput  = document.getElementById('npwp-input');

    /**
     * Toggle between email and NPWP fields in the instansi register form.
     * @param {string} type  'email' | 'npwp'
     */
    function switchIdentityType(type) {
        if (!fieldEmail || !fieldNpwp) { return; }
        const useNpwp = type === 'npwp';
        fieldEmail.classList.toggle('hidden', useNpwp);
        fieldNpwp.classList.toggle('hidden', !useNpwp);

        // Toggle required attributes
        if (emailInput) {
            emailInput.required = !useNpwp;
            if (useNpwp) { emailInput.value = ''; }
        }
        if (npwpInput) {
            npwpInput.required = useNpwp;
            if (!useNpwp) { npwpInput.value = ''; }
        }
    }

    document.querySelectorAll('input[name="identity_type"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            switchIdentityType(radio.value);
        });
    });

    // ── NPWP auto-format ──────────────────────────────────────────────────────

    /**
     * Format a raw digit string into XX.XXX.XXX.X-XXX.XXX (max 15 digits).
     * @param {string} raw - Digits only.
     * @returns {string}
     */
    function formatNpwp(raw) {
        // Trim to 15 digits
        const d = raw.slice(0, 15);
        let result = '';
        for (let i = 0; i < d.length; i++) {
            if (i === 2 || i === 5 || i === 8) {
                result += '.';
            } else if (i === 9) {
                result += '-';
            }
            result += d[i];
        }
        return result;
    }

    if (npwpInput) {
        npwpInput.addEventListener('input', function (e) {
            const pos    = this.selectionStart;
            const before = this.value;
            const digits = before.replace(/[^0-9]/g, '');
            const formatted = formatNpwp(digits);
            this.value = formatted;

            // Preserve caret position heuristically
            const added = formatted.length - before.length;
            try {
                this.setSelectionRange(pos + added, pos + added);
            } catch (_) { /* ignore */ }
        });

        // On paste, strip and reformat
        npwpInput.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted  = (e.clipboardData || window.clipboardData).getData('text');
            const digits  = pasted.replace(/[^0-9]/g, '');
            this.value    = formatNpwp(digits);
        });
    }

    // ── Re-run lucide after DOM interactions ─────────────────────────────────
    // The guest layout already calls createIcons on DOMContentLoaded.
    // We call it again here in case any icons were added dynamically or
    // replaced by togglePassword.
    if (window.lucide) {
        window.lucide.createIcons();
    }

}());
</script>
@endsection
