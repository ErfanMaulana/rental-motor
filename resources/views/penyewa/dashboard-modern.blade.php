@extends('layouts.app-modern')

@section('title', 'Dashboard Penyewa')

@section('header')
    <h1 class="text-2xl font-bold text-gray-900">Dashboard Penyewa</h1>
    <p class="mt-1 text-sm text-gray-500">Temukan dan sewa motor impian Anda dengan mudah</p>
@endsection

@section('navigation')
    <div class="space-y-1">
        <div class="px-3 mb-2">
            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Menu Utama</p>
        </div>
        <a href="{{ route('penyewa.dashboard') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-800/50 rounded-lg">
            <i class="bi bi-speedometer2 w-5 mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('penyewa.motors') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium text-blue-100 hover:bg-blue-800/50 hover:text-white rounded-lg transition">
            <i class="bi bi-motorcycle w-5 mr-3"></i>
            Daftar Motor
        </a>
        <a href="{{ route('penyewa.bookings') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium text-blue-100 hover:bg-blue-800/50 hover:text-white rounded-lg transition">
            <i class="bi bi-calendar-check w-5 mr-3"></i>
            Pemesanan Saya
        </a>
        <a href="{{ route('penyewa.payment-history') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium text-blue-100 hover:bg-blue-800/50 hover:text-white rounded-lg transition">
            <i class="bi bi-credit-card w-5 mr-3"></i>
            Riwayat Pembayaran
        </a>
        <a href="{{ route('penyewa.reports') }}" 
           class="flex items-center px-3 py-2 text-sm font-medium text-blue-100 hover:bg-blue-800/50 hover:text-white rounded-lg transition">
            <i class="bi bi-file-earmark-text w-5 mr-3"></i>
            Laporan
        </a>
    </div>
@endsection

@section('content')
    <!-- Verification Status Alert -->
    @if(!$isVerified)
    <div class="mb-6 rounded-lg bg-yellow-50 p-4 border border-yellow-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-exclamation-triangle-fill text-yellow-400 text-2xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-yellow-800">Akun Belum Diverifikasi</h3>
                <p class="mt-1 text-sm text-yellow-700">
                    Akun Anda masih dalam proses verifikasi oleh admin. 
                    Anda <strong>belum dapat menyewa motor</strong> hingga akun diverifikasi.
                    Silakan tunggu atau hubungi admin untuk informasi lebih lanjut.
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-check-circle-fill text-green-400 text-2xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-green-800">Akun Terverifikasi</h3>
                <p class="mt-1 text-sm text-green-700">
                    Selamat! Akun Anda sudah terverifikasi dan Anda dapat menyewa motor.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-blue-100">
                            <i class="bi bi-calendar-check text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pemesanan</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-yellow-100">
                            <i class="bi bi-clock text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sedang Berlangsung</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $activeBookings }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-green-100">
                            <i class="bi bi-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Selesai</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $completedBookings }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-cyan-100">
                            <i class="bi bi-currency-dollar text-2xl text-cyan-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pengeluaran</dt>
                            <dd class="text-xl font-bold text-gray-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Featured Motors -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-star mr-2 text-yellow-500"></i>
                        Motor Rekomendasi
                    </h2>
                    <a href="{{ route('penyewa.motors') }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                        Lihat Semua
                        <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if($featuredMotors->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($featuredMotors as $motor)
                            <div class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                                @if($motor->photo)
                                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                        <img src="{{ Storage::url($motor->photo) }}" 
                                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
                                             alt="{{ $motor->brand }} {{ $motor->model }}">
                                    </div>
                                @else
                                    <div class="h-48 bg-gray-100 flex items-center justify-center">
                                        <i class="bi bi-motorcycle text-gray-400 text-5xl"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $motor->brand }} {{ $motor->model }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ $motor->type_cc }} • {{ $motor->year }}</p>
                                    @if($motor->rentalRate)
                                        <p class="text-lg font-bold text-blue-600 mb-3">
                                            Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span>
                                        </p>
                                    @endif
                                    <button type="button" 
                                            onclick="showMotorDetail({{ $motor->id }})"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                                        <i class="bi bi-eye mr-2"></i>
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-motorcycle text-gray-300 text-6xl"></i>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada motor tersedia</h3>
                            <p class="mt-2 text-sm text-gray-500">Motor yang tersedia akan ditampilkan di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Aksi Cepat</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('penyewa.motors') }}" 
                       class="block w-full px-4 py-3 text-sm font-medium text-center text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-sm">
                        <i class="bi bi-search mr-2"></i>
                        Cari Motor
                    </a>
                    <a href="{{ route('penyewa.bookings') }}" 
                       class="block w-full px-4 py-3 text-sm font-medium text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <i class="bi bi-calendar-check mr-2"></i>
                        Lihat Pemesanan
                    </a>
                    <a href="{{ route('penyewa.reports') }}" 
                       class="block w-full px-4 py-3 text-sm font-medium text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <i class="bi bi-file-earmark-text mr-2"></i>
                        Unduh Laporan
                    </a>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Pemesanan Terbaru</h2>
                </div>
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $booking->motor->brand }} {{ $booking->motor->model }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'completed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada pemesanan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function showMotorDetail(motorId) {
    window.location.href = `/penyewa/motors`;
}
</script>
@endpush
