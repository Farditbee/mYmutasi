<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>mY Mutasi - Pencatatan Keuangan Modern</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=poppins:400,500,600,700" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('img/logo.png') }}" alt="mY Mutasi Logo" class="h-10 w-10">
                    <h1 class="text-xl font-bold text-gray-900">mY Mutasi</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Kelola Keuangan dengan
                <span class="text-yellow-300">Mudah & Modern</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                Aplikasi pencatatan keuangan yang membantu Anda mengatur transaksi, 
                mengkategorikan pengeluaran, dan memantau kesehatan finansial dengan mudah.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Mulai Gratis
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Ga ada fitur unggulan, sama aja kaya aplikasi pencatatan keuangan yang lain, Ada pencatatan, Laporan dsb.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="card-hover bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                    <div class="text-4xl feature-icon mb-4"><i class="fas fa-hand-holding-usd"></i></div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Pencatatan Transaksi</h3>
                    <p class="text-gray-600">
                        Catat semua pemasukan dan pengeluaran dengan mudah. 
                        Interface yang intuitif memudahkan input data harian.
                    </p>
                </div>
                
                <div class="card-hover bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                    <div class="text-4xl feature-icon mb-4"><i class="fas fa-chart-line"></i></div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Laporan Visual</h3>
                    <p class="text-gray-600">
                        Lihat pola pengeluaran dengan grafik dan chart yang mudah dipahami. 
                        Analisis keuangan jadi lebih sederhana.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-blue-600 mb-2">100%</div>
                    <div class="text-gray-600">Gratis</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-green-600 mb-2">∞</div>
                    <div class="text-gray-600">Transaksi</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-purple-600 mb-2">24/7</div>
                    <div class="text-gray-600">Akses</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="text-3xl font-bold text-orange-600 mb-2">🔒</div>
                    <div class="text-gray-600">Aman</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Cara Kerja</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Mulai kelola keuangan Anda dalam 3 langkah sederhana
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Daftar Akun</h3>
                    <p class="text-gray-600">
                        Buat akun gratis dalam hitungan detik. 
                        Gaperlu kartu kredit kartu atm dll, tentuin akun yang gampang diinget aja biar ga lupa.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Catat Transaksi</h3>
                    <p class="text-gray-600">
                        Mulai catat pemasukan dan pengeluaran harian. 
                        Atur kategori sesuai kebutuhan Anda.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-purple-600">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analisis & Monitor</h3>
                    <p class="text-gray-600">
                        Lihat laporan dan analisis keuangan. 
                        Buat keputusan finansial yang lebih baik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Siap Mengatur Keuangan Anda
            </h2>
            <p class="text-xl mb-8 text-blue-100">
                mY Mutasi adalah aplikasi simple yang mudah digunakan dan sangat membantu, jadi terserah mau pake ya pake, engga mau yaudah engga papa
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block">
                    Buka Dashboard Saya
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block">
                    Mulai Sekarang - Gratis!
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('img/logo.png') }}" alt="mY Mutasi Logo" class="h-8 w-8">
                        <h3 class="text-xl font-bold">mY Mutasi</h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Aplikasi pencatatan keuangan modern yang membantu Anda 
                        mengatur dan memantau kesehatan finansial dengan mudah.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Pencatatan Transaksi</li>
                        <li>Kategori Custom</li>
                        <li>Laporan Visual</li>
                        <li>Export Data</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Panduan Penggunaan</li>
                        <li>FAQ</li>
                        <li>Kontak Support</li>
                        <li>Privacy Policy</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} mY Mutasi. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
