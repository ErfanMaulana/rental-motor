@extends('layouts.fann')

@section('title', 'Dashboard Pemilik')

@section('content')
<!-- Verification Status Alert -->
@if(!$isVerified)
    <div class="mb-6 rounded-lg bg-yellow-50 p-4 border-l-4 border-yellow-400 shadow-sm">
        <div class="flex items-center">
            <i class="bi bi-shield-exclamation text-yellow-500 text-2xl"></i>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-yellow-800">Akun Belum Terverifikasi</h3>
                <p class="mt-1 text-sm text-yellow-700">
                    Akun Anda belum diverifikasi. Anda dapat melihat dashboard, tetapi tidak dapat mendaftarkan motor baru. Silakan tunggu admin memverifikasi akun Anda.
                </p>
            </div>
        </div>
    </div>
@else
    <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400 shadow-sm">
        <div class="flex items-center">
            <i class="bi bi-shield-check text-green-500 text-2xl"></i>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-green-800">Akun Terverifikasi</h3>
                <p class="mt-1 text-sm text-green-700">
                    Selamat! Akun Anda telah diverifikasi dan Anda dapat mendaftarkan motor untuk disewakan.
                </p>
            </div>
        </div>
    </div>
@endif

<!-- Error Messages -->
@if($errors->has('verification'))
    <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-400 shadow-sm">
        <div class="flex items-center">
            <i class="bi bi-exclamation-triangle text-red-500 text-2xl"></i>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-red-800">Akses Ditolak</h3>
                <p class="mt-1 text-sm text-red-700">{{ $errors->first('verification') }}</p>
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
                        <i class="bi bi-motorcycle text-2xl text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Motor</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $totalMotors }}</dd>
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Motor Tersedia</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $availableMotors }}</dd>
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Disewa</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $rentedMotors }}</dd>
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pendapatan</dt>
                        <dd class="text-xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Motor Saya -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="bi bi-motorcycle mr-2 text-blue-600"></i>
                    Motor Saya
                </h2>
                <a href="{{ route('pemilik.motors') }}" 
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                    Lihat Semua
                    <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                @if($recentMotors->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif/Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentMotors as $motor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($motor->photo)
                                        <img src="{{ Storage::url($motor->photo) }}" 
                                             alt="{{ $motor->brand }} {{ $motor->model }}" 
                                             class="h-12 w-12 rounded object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-100 flex items-center justify-center">
                                            <i class="bi bi-motorcycle text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $motor->brand }}</div>
                                        <div class="text-sm text-gray-500">{{ $motor->model }} • {{ $motor->year }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $motor->cc }}cc
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($motor->is_verified)
                                    @php
                                        $currentStatus = $motor->getCurrentStatus();
                                        $currentBooking = $motor->getCurrentBooking();
                                    @endphp
                                    
                                    @if($currentStatus === 'rented')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-person-check mr-1"></i>Sedang Disewa
                                        </span>
                                        @if($currentBooking)
                                            <div class="text-xs text-gray-500 mt-1">{{ $currentBooking->renter->name }}</div>
                                        @endif
                                    @elseif($currentStatus === 'available')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle mr-1"></i>Tersedia
                                        </span>
                                    @elseif($currentStatus === 'maintenance')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="bi bi-tools mr-1"></i>Maintenance
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Verifikasi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($motor->rentalRate)
                                    <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</div>
                                @else
                                    <span class="text-sm text-gray-500">Belum diset</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('pemilik.motor.detail', $motor->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-12">
                    <i class="bi bi-motorcycle text-gray-300 text-6xl"></i>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada motor yang didaftarkan</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai daftarkan motor Anda untuk disewakan</p>
                    <div class="mt-6">
                        <a href="{{ route('pemilik.motor.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Daftarkan Motor
                        </a>
                    </div>
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
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="bi bi-lightning-charge mr-2 text-yellow-500"></i>
                    Aksi Cepat
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('pemilik.motor.create') }}" 
                   class="block w-full px-4 py-3 text-sm font-medium text-center text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-sm">
                    <i class="bi bi-plus-circle mr-2"></i>
                    Daftarkan Motor Baru
                </a>
                <a href="{{ route('pemilik.motors') }}" 
                   class="block w-full px-4 py-3 text-sm font-medium text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="bi bi-list-ul mr-2"></i>
                    Kelola Motor
                </a>
                <a href="{{ route('pemilik.bookings') }}" 
                   class="block w-full px-4 py-3 text-sm font-medium text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="bi bi-calendar-check mr-2"></i>
                    Lihat Pemesanan
                </a>
                <a href="{{ route('pemilik.revenue.report') }}" 
                   class="block w-full px-4 py-3 text-sm font-medium text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="bi bi-graph-up mr-2"></i>
                    Laporan Pendapatan
                </a>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="bi bi-lightbulb mr-2 text-yellow-500"></i>
                    Tips Sukses
                </h2>
            </div>
            <div class="p-6">
                <ul class="space-y-3">
                    <li class="flex">
                        <i class="bi bi-check-circle text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm text-gray-600">Pastikan motor selalu dalam kondisi prima</span>
                    </li>
                    <li class="flex">
                        <i class="bi bi-check-circle text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm text-gray-600">Upload foto motor yang menarik dan berkualitas</span>
                    </li>
                    <li class="flex">
                        <i class="bi bi-check-circle text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm text-gray-600">Set tarif yang kompetitif dan wajar</span>
                    </li>
                    <li class="flex">
                        <i class="bi bi-check-circle text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm text-gray-600">Respon cepat terhadap pemesanan pelanggan</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection