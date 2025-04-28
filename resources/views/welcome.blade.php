<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PSPIG - Platform Sewa Workspace</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">PSPIG</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
            @if (Route::has('login'))
                    @auth
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                                </li>
                    @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                                </li>
                        @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container text-center">
                <h1 class="display-4 mb-4">Temukan Workspace Terbaik untuk Produktivitasmu</h1>
                <p class="lead mb-5">Platform sewa workspace yang memudahkan Anda menemukan tempat kerja nyaman sesuai kebutuhan</p>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Mulai Sekarang</a>
                @endif
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-5">Mengapa Memilih PSPIG?</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card feature-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                                <h3 class="card-title h5">Mudah Mencari</h3>
                                <p class="card-text">Temukan workspace yang sesuai dengan kebutuhanmu dengan mudah dan cepat</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card feature-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-3x mb-3 text-primary"></i>
                                <h3 class="card-title h5">Fleksibel</h3>
                                <p class="card-text">Sewa per jam sesuai kebutuhanmu, tanpa perlu berlangganan jangka panjang</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card feature-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-alt fa-3x mb-3 text-primary"></i>
                                <h3 class="card-title h5">Aman & Terpercaya</h3>
                                <p class="card-text">Semua workspace telah terverifikasi dan dijamin keamanannya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-white py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>PSPIG</h5>
                        <p>Platform Sewa Workspace Indonesia</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>&copy; {{ date('Y') }} PSPIG. All rights reserved.</p>
                    </div>
                </div>
        </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
    </body>
</html>
