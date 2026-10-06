<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-display font-bold text-xl text-text-primary tracking-tight">Daftar Akun Baru</h2>
        <p class="text-xs text-text-secondary mt-1">Bergabung bersama masyarakat Tulungagung untuk menebar kebaikan.</p>
    </div>

    <form method="POST" action="{{ route('donatur.register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input id="name" class="block w-full pl-10 pr-4 py-2.5 text-sm border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input id="email" class="block w-full pl-10 pr-4 py-2.5 text-sm border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <input id="password" class="block w-full pl-10 pr-4 py-2.5 text-sm border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <input id="password_confirmation" class="block w-full pl-10 pr-4 py-2.5 text-sm border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-sm font-semibold rounded-xl">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Daftar Sekarang
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 border-t border-border text-center">
            <p class="text-xs text-text-secondary">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-primary-hover transition-colors">
                    Masuk ke Akun Anda
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
