<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tracking - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <x-brand-styles />
    </head>
    <body class="bg-white font-sans antialiased text-slate-900">
        <header class="bg-white py-6 border-b">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <span class="text-2xl font-black text-blue-600 tracking-tight">{{ config('app.name') }}</span>
            </div>
        </header>

        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <footer class="bg-slate-50 border-t py-12">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-sm text-slate-500">&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
            </div>
        </footer>
    </body>
</html>
