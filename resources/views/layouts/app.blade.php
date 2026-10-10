<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'SI-PEDULI Pemkab Tulungagung') }}</title>

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #087F5B;
            --color-primary-dark: #066A4C;
            --color-secondary: #123B32;
            --color-surface: #F6F8F7;
            --color-border: #D9E2DE;
        }

        body {
            background-color: #F6F8F7;
            color: #17211E;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            letter-spacing: -0.02em;
        }

        /* Impeccable Craft: Themed Selection & Focus Rings */
        ::selection {
            background-color: #E6F4EF;
            color: #066A4C;
        }

        :focus-visible {
            outline: 2px solid #087F5B;
            outline-offset: 2px;
        }

        /* Smooth, refined scrollbars */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #EEF3F1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #B9CCC4;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #087F5B;
        }

        /* Subtle tabular numbers for financial figures */
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F6F8F7] text-[#17211E] antialiased min-h-screen flex flex-col">
    @include('layouts.navigation')

    <!-- Flash Notifications -->
    @if (session('status') || session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xs">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span class="text-xs sm:text-sm font-medium">{{ session('status') ?? session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="bg-red-50 border border-red-300 text-red-900 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xs">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="flex-1 flex flex-col">
        @yield('content')
        {{ $slot ?? '' }}
    </div>

    @include('layouts.footer')

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
