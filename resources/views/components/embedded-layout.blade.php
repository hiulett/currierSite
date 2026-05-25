<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Widget - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <x-brand-styles />
        <style>
            body { background: transparent !important; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-900">
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
