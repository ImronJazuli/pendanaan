<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-display font-bold text-xl text-text-primary tracking-tight">Masuk ke Akun Anda</h2>
        <p class="text-xs text-text-secondary mt-1">Gunakan akun donatur, perwakilan instansi, atau verifikator pemkab.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" autocomplete="off" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input id="email" class="block w-full pl-10 pr-4 py-2.5 text-sm border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="off" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs font-semibold text-text-secondary uppercase tracking-wider" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-primary hover:text-primary-hover transition-colors" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <input id="password" class="block w-full pl-10 pr-4 py-2.5 text-sm border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/30" name="remember">
                <span class="text-xs text-text-secondary">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-sm font-semibold rounded-xl">
                <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                Masuk ke Portal
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 border-t border-border text-center">
            <p class="text-xs text-text-secondary">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-primary-hover transition-colors">
                    Daftar sebagai Donatur Baru
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
