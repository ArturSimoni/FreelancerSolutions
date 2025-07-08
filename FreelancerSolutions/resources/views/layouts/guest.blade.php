<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-gray-900">
        <div
            class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0 px-4 sm:px-0"
        >
            <a href="/" class="mb-8">
                <x-application-logo
                    class="w-28 h-28 text-primary-600 dark:text-primary-400"
                />
            </a>

            <div
                class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-lg px-8 py-10 sm:px-12 sm:py-12"
            >
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
