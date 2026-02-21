@extends('layouts.welcome')

@section('title', 'FannRental - Platform Rental Motor Terpercaya')

@section('content')
<!-- Hero Banner Section -->
<div class="hero-banner">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold text-white mb-3">
                        Rental Motor <span class="text-warning">Terpercaya</span> di Indonesia
                    </h1>
                    <p class="lead text-white-75 mb-4">
                        Nikmati pengalaman berkendara yang aman dan nyaman dengan koleksi motor terlengkap kami. 
                        Dari motor matic untuk dalam kota hingga motor sport untuk perjalanan jauh.
                    </p>
                    @guest
                    @else
                        <div class="d-flex gap-3 mb-4">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-lg px-4 py-3">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                                </a>
                            @elseif(auth()->user()->role === 'pemilik')
                                <a href="{{ route('pemilik.dashboard') }}" class="btn btn-warning btn-lg px-4 py-3">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Pemilik
                                </a>
                            @else
                                <a href="{{ route('penyewa.dashboard') }}" class="btn btn-warning btn-lg px-4 py-3">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Penyewa
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="position-relative">
                        <div class="promo-poster">
                            <div class="poster-bg p-4 rounded-4 shadow-lg">
                                <div class="text-center mb-3">
                                    <h2 class="fw-bold text-primary mb-2">PROMO SPESIAL!</h2>
                                    <div class="badge bg-danger fs-6 px-3 py-2 mb-3">HEMAT HINGGA 30%</div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="promo-item bg-light p-3 rounded-3 text-center">
                                            <i class="text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="fw-semibold">Motor Matic</h6>
                                            <small class="text-muted">Mulai dari</small>
                                            <div class="price">
                                                <span class="text-decoration-line-through text-muted">Rp 100K</span>
                                                <strong class="text-primary d-block">Rp 75K/hari</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="promo-item bg-light p-3 rounded-3 text-center">
                                            <i class="text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="fw-semibold">Motor Sport</h6>
                                            <small class="text-muted">Mulai dari</small>
                                            <div class="price">
                                                <span class="text-decoration-line-through text-muted">Rp 200K</span>
                                                <strong class="text-primary d-block">Rp 150K/hari</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>Promo berlaku sampai akhir bulan!
                                    </small>
                                </div>
                                
                                <div class="d-flex gap-2 mt-3">
                                    @guest
                                        <a href="{{ route('register') }}" class="btn btn-primary flex-fill">
                                            <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                        </a>
                                    @else
                                        <a href="#kategori-motor" class="btn btn-primary flex-fill">
                                            <i class="bi bi-search me-1"></i>Pilih Motor
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-3">Mengapa Memilih FannRental?</h2>
                <p class="text-muted mb-5">Kami berkomitmen memberikan layanan rental motor terbaik untuk Anda</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Aman & Terpercaya</h5>
                    <p class="text-muted">Semua motor telah melalui verifikasi ketat dari admin kami. Dokumen lengkap dan kondisi prima.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-currency-dollar text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Harga Terjangkau</h5>
                    <p class="text-muted">Berbagai pilihan motor dengan harga kompetitif. Dapatkan promo menarik setiap bulannya.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-clock text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Proses Cepat</h5>
                    <p class="text-muted">Booking mudah dan konfirmasi langsung. Layanan 24/7 siap membantu kapan saja.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Motor Available Section -->
<section id="kategori-motor" class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-3">Motor Tersedia</h2>
                <p class="text-muted mb-5">Lihat koleksi motor yang sudah terverifikasi dan siap disewa</p>
            </div>
        </div>
        
        @if($motors->count() > 0)
        <div class="row g-4">
            @foreach($motors as $motor)
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <!-- Badge Status -->
                    <div class="position-absolute top-0 start-0 m-3 z-1">
                        @php
                            $currentStatus = $motor->getCurrentStatus();
                        @endphp
                        
                        @if($currentStatus === 'available')
                            <span class="badge bg-success">Tersedia</span>
                        @elseif($currentStatus === 'rented')
                            <span class="badge bg-warning">Sedang Disewa</span>
                        @elseif($currentStatus === 'maintenance')
                            <span class="badge bg-secondary">Maintenance</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($currentStatus) }}</span>
                        @endif
                    </div>
                    
                    <!-- Motor Image -->
                    <div class="position-relative" style="height: 250px; overflow: hidden;">
                        @if($motor->photo)
                            <img src="{{ Storage::url($motor->photo) }}" 
                                 class="card-img-top w-100 h-100 object-fit-cover" 
                                 alt="{{ $motor->brand }} {{ $motor->model }}"
                                 style="object-fit: cover;">
                        @else
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <!-- Brand & Model -->
                        <h5 class="card-title fw-bold mb-2">{{ $motor->brand }}</h5>
                        <div class="d-flex align-items-center gap-2 mb-3 text-muted small">
                            <span><i class="bi bi-tag me-1"></i>{{ $motor->model }}</span>
                            <span>•</span>
                            <span><i class="bi bi-speedometer me-1"></i>{{ $motor->type_cc }}</span>
                        </div>
                        
                        <!-- Description -->
                        @if($motor->description)
                        <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $motor->description }}
                        </p>
                        @else
                        <p class="card-text text-muted small mb-3">Motor masih bagus</p>
                        @endif
                        
                        <!-- Pricing Section -->
                        @if($motor->rentalRate)
                        <div class="border-top pt-3 mb-3">
                            <div class="small text-muted mb-2">HARGA SEWA</div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Harian</span>
                                <span class="fw-semibold text-primary">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Mingguan</span>
                                <span class="fw-semibold text-primary">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Bulanan</span>
                                <span class="fw-semibold text-primary">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Verified Badge -->
                        <div class="text-center mt-3">
                            <small class="text-success">
                                <i class="bi bi-patch-check-fill me-1"></i>
                                Diverifikasi {{ $motor->verified_at->locale('id')->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted mb-3" style="font-size: 4rem;"></i>
            <p class="text-muted">Belum ada motor yang tersedia saat ini</p>
        </div>
        @endif
    </div>
</section>

<!-- Bantuan Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-3">Bantuan</h2>
                <p class="text-muted mb-5">Panduan lengkap untuk memudahkan pengalaman rental motor Anda</p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- FAQ -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="fw-bold mb-0">FAQ</h4>
                        </div>
                        <p class="text-muted mb-4">
                            Frequently Asked Questions (FAQ) berisi jawaban dari pertanyaan-pertanyaan yang sering ditanyakan seputar layanan rental motor kami.
                        </p>
                        
                        <div class="accordion accordion-flush" id="accordionFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        <strong>Apa saja persyaratan untuk menyewa motor?</strong>
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Anda harus memiliki KTP, SIM C, dan melakukan deposit sesuai ketentuan pemilik motor.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        <strong>Bagaimana cara pembayaran?</strong>
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Pembayaran dapat dilakukan melalui berbagai metode: <strong>Dana</strong>, <strong>GoPay</strong>, <strong>ShopeePay</strong>, atau <strong>Transfer Bank</strong>. Semua pembayaran akan dikonfirmasi oleh pemilik motor.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        <strong>Apakah bisa sewa motor untuk jangka panjang?</strong>
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Ya, kami menyediakan paket sewa mingguan dan bulanan dengan harga lebih hemat.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cara Booking -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Cara Booking</h4>
                        </div>
                        <p class="text-muted mb-4">
                            Panduan langkah demi langkah untuk melakukan booking motor dengan mudah dan cepat di platform FannRental.
                        </p>
                        
                        <div class="booking-steps">
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <strong>1</strong>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-1">Daftar / Login</h6>
                                    <p class="text-muted mb-0 small">Buat akun atau login jika sudah memiliki akun</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <strong>2</strong>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-1">Pilih Motor</h6>
                                    <p class="text-muted mb-0 small">Cari dan pilih motor yang sesuai dengan kebutuhan Anda</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <strong>3</strong>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-1">Isi Form Booking</h6>
                                    <p class="text-muted mb-0 small">Tentukan tanggal sewa dan isi data yang diperlukan</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <strong>4</strong>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-1">Bayar & Konfirmasi</h6>
                                    <p class="text-muted mb-0 small">Lakukan pembayaran dan tunggu konfirmasi dari pemilik</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <strong>5</strong>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-1">Ambil Motor</h6>
                                    <p class="text-muted mb-0 small">Setelah disetujui, Anda dapat mengambil motor sesuai jadwal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Syarat & Ketentuan dan Kebijakan Privasi -->
            <div class="col-12">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-file-text text-primary me-2" style="font-size: 1.5rem;"></i>
                                    <h5 class="fw-semibold mb-0">Syarat & Ketentuan</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    Informasi lengkap mengenai aturan dan ketentuan yang berlaku dalam menggunakan layanan rental motor kami.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-shield-lock text-primary me-2" style="font-size: 1.5rem;"></i>
                                    <h5 class="fw-semibold mb-0">Kebijakan Privasi</h5>
                                </div>
                                <p class="text-muted mb-0">
                                    Komitmen kami dalam menjaga keamanan dan privasi data pribadi Anda selama menggunakan platform FannRental.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection