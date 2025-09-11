<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>mY Mutasi</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('img/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- SweetAlert Messages -->
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    timer: 5000,
                    timerProgressBar: true
                });
            </script>
        @endif
        
        @if(session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            </script>
        @endif
        
        @if($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    showConfirmButton: true
                });
            </script>
        @endif
        
        @stack('scripts')
        
        <!-- Theme Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const themeToggle = document.getElementById('theme-toggle');
                const themeToggleMobile = document.getElementById('theme-toggle-mobile');
                const themeIcon = document.getElementById('theme-icon');
                const themeIconMobile = document.getElementById('theme-icon-mobile');
                const themeText = document.getElementById('theme-text');
                const themeTextMobile = document.getElementById('theme-text-mobile');
                const body = document.body;
                
                // Check for saved theme preference or default to 'normal'
                const currentTheme = localStorage.getItem('theme') || 'normal';
                
                // Apply saved theme
                if (currentTheme === 'cartoon') {
                    body.classList.add('cartoon-theme');
                    updateThemeUI('cartoon');
                } else {
                    body.classList.remove('cartoon-theme');
                    updateThemeUI('normal');
                }
                
                // Theme toggle function
                function toggleTheme() {
                    if (body.classList.contains('cartoon-theme')) {
                        // Switch to normal theme
                        body.classList.remove('cartoon-theme');
                        localStorage.setItem('theme', 'normal');
                        updateThemeUI('normal');
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Tema Diubah!',
                            text: 'Tema normal telah diaktifkan',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        // Switch to cartoon theme
                        body.classList.add('cartoon-theme');
                        localStorage.setItem('theme', 'cartoon');
                        updateThemeUI('cartoon');
                        
                        // Show fun success message
                        Swal.fire({
                            icon: 'success',
                            title: '🎨 Tema Alter Aktif!',
                            text: 'Selamat datang di dunia yang penuh warna! 🌈',
                            showConfirmButton: false,
                            timer: 2000,
                            toast: true,
                            position: 'top-end',
                            background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            color: 'white'
                        });
                    }
                }
                
                // Update theme UI elements
                function updateThemeUI(theme) {
                    if (theme === 'cartoon') {
                        if (themeIcon) {
                            themeIcon.className = 'fas fa-magic text-lg';
                        }
                        if (themeIconMobile) {
                            themeIconMobile.className = 'fas fa-magic text-lg mr-3';
                        }
                        if (themeText) {
                            themeText.textContent = 'Alter';
                        }
                        if (themeTextMobile) {
                            themeTextMobile.textContent = 'Mode Alter';
                        }
                    } else {
                        if (themeIcon) {
                            themeIcon.className = 'fas fa-palette text-lg';
                        }
                        if (themeIconMobile) {
                            themeIconMobile.className = 'fas fa-palette text-lg mr-3';
                        }
                        if (themeText) {
                            themeText.textContent = 'Tema';
                        }
                        if (themeTextMobile) {
                            themeTextMobile.textContent = 'Ganti Tema';
                        }
                    }
                }
                
                // Add event listeners
                if (themeToggle) {
                    themeToggle.addEventListener('click', toggleTheme);
                }
                
                if (themeToggleMobile) {
                    themeToggleMobile.addEventListener('click', toggleTheme);
                }
                
                // Removed automatic animations for better UX
                
                // Removed Konami code easter egg to avoid animations
            });
        </script>
    </body>
</html>
