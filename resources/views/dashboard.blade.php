@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#087f5b] to-[#0d7c6b] flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-[#087f5b] to-[#0d7c6b] flex items-center justify-center mx-auto">
                <span class="text-white font-bold text-2xl">P</span>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-[#2c3e50] mb-2">Portal Pendanaan Sosial</h1>
        <p class="text-[#5261a4] mb-8">Tulungagung</p>

        <p class="text-[#5261a4] mb-8">Hai, {{ auth()->user()->name }}!</p>

        <div class="space-y-4">
            @if (auth()->user()->peran === 'institution_user')
                <a href="{{ route('dashboard.instansi') }}" class="block w-full py-3 px-4 bg-[#087f5b] text-white font-bold rounded-lg hover:bg-[#0d7c6b] transition">
                    📋 Buka Dashboard Instansi
                </a>
            @elseif (auth()->user()->peran === 'donatur')
                <a href="{{ route('dashboard.donatur') }}" class="block w-full py-3 px-4 bg-[#087f5b] text-white font-bold rounded-lg hover:bg-[#0d7c6b] transition">
                    💝 Buka Dashboard Donatur
                </a>
            @elseif (auth()->user()->peran === 'admin')
                <a href="{{ route('dashboard.admin') }}" class="block w-full py-3 px-4 bg-[#087f5b] text-white font-bold rounded-lg hover:bg-[#0d7c6b] transition">
                    ⚙️ Buka Dashboard Admin
                </a>
            @endif

            <a href="{{ route('kausa.index') }}" class="block w-full py-3 px-4 border-2 border-[#087f5b] text-[#087f5b] font-bold rounded-lg hover:bg-[#f0f9f6] transition">
                📚 Lihat Katalog Kausa
            </a>
        </div>

        <hr class="my-6 border-[#e9ecef]">

        <a href="{{ route('profile.edit') }}" class="text-sm text-[#5261a4] hover:text-[#087f5b] font-medium">
            ⚙️ Kelola Profil
        </a>
    </div>
</div>
@endsection
