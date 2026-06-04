<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#F4F9FA]">
            
            <div class="mb-6 flex justify-center bg-[#F4FAF2]/60 p-4 rounded-lg w-full max-w-md">
                <img src="{{ asset('images/logo_adeafwa.png') }}" alt="Ade Afwa Boutique" class="h-20 object-contain" />
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-sm border border-gray-100 overflow-hidden sm:rounded-sm">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>