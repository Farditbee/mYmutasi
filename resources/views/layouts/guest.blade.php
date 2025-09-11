<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>mY Mutasi</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('img/favicon.svg') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=poppins:400,500,600,700" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .auth-card {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .auth-card:hover {
                background: rgba(255, 255, 255, 0.98);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen gradient-bg flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Logo Section -->
            <div class="mb-8">
                <a href="/" class="flex items-center space-x-3">
                    <img src="{{ asset('img/logo.png') }}" alt="mY Mutasi" class="w-16 h-16 rounded-full shadow-lg">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">mY Mutasi</h1>
                        <p class="text-blue-100 text-sm">Pencatatan Keuangan Modern</p>
                    </div>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="w-full sm:max-w-md px-8 py-8 auth-card shadow-2xl rounded-2xl">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center text-blue-100">
                <p class="text-sm">&copy; {{ date('Y') }} mY Mutasi. Semua hak dilindungi.</p>
            </div>
        </div>
    </body>
</html>
