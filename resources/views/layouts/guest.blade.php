<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-purple-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-900 dark:to-indigo-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-lg border border-purple-200 dark:border-gray-700">
                <div class="flex flex-col justify-center items-center mb-6">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-purple-600 dark:text-purple-400" />
                    </a>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-2">CodeBattle</p>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
