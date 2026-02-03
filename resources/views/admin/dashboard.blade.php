@extends('layouts.fann')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Primary Statistics -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-blue-100">
                            <i class="bi bi-people text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</dd>
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
                            <i class="bi bi-scooter text-2xl text-cyan-600"></i>
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
                        <div class="p-3 rounded-lg bg-yellow-100">
                            <i class="bi bi-calendar-check text-2xl text-yellow-600"></i>
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
                        <div class="p-3 rounded-lg bg-green-100">
                            <i class="bi bi-currency-dollar text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pendapatan</dt>
                            <dd class="text-lg font-bold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-700">{{ $totalPenyewa }}</div>
            <div class="text-xs text-blue-600 mt-1">Penyewa</div>
        </div>
        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-cyan-700">{{ $totalPemilik }}</div>
            <div class="text-xs text-cyan-600 mt-1">Pemilik</div>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-700">{{ $pendingMotorsCount }}</div>
            <div class="text-xs text-yellow-600 mt-1">Perlu Verifikasi</div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-700">{{ $availableMotors }}</div>
            <div class="text-xs text-green-600 mt-1">Motor Tersedia</div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-700">{{ $pendingBookings }}</div>
            <div class="text-xs text-purple-600 mt-1">Booking Pending</div>
        </div>
        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-pink-700">{{ $activeBookings }}</div>
            <div class="text-xs text-pink-600 mt-1">Booking Aktif</div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-calendar-check mr-2 text-blue-600"></i>
                        Pemesanan Terbaru
                    </h2>
                    <a href="{{ route('admin.bookings') }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                        Lihat Semua
                        <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    @if($recentBookings->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'completed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-12">
                        <i class="bi bi-calendar-x text-gray-300 text-6xl"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada pemesanan</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pending Actions -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Perlu Tindakan</h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($pendingMotorsCount > 0)
                    <a href="{{ route('admin.motors') }}?status=pending" 
                       class="block p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Motor Perlu Verifikasi</p>
                                <p class="text-xs text-yellow-600 mt-1">{{ $pendingMotorsCount }} motor menunggu</p>
                            </div>
                            <i class="bi bi-arrow-right text-yellow-600"></i>
                        </div>
                    </a>
                    @endif
                    
                    @if($pendingBookings > 0)
                    <a href="{{ route('admin.bookings') }}?status=pending" 
                       class="block p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-800">Booking Pending</p>
                                <p class="text-xs text-blue-600 mt-1">{{ $pendingBookings }} booking menunggu</p>
                            </div>
                            <i class="bi bi-arrow-right text-blue-600"></i>
                        </div>
                    </a>
                    @endif

                    @if($pendingMotorsCount == 0 && $pendingBookings == 0)
                    <div class="text-center py-6">
                        <i class="bi bi-check-circle text-green-500 text-4xl"></i>
                        <p class="text-sm text-gray-600 mt-2">Semua tindakan selesai!</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Statistik Cepat</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Motor Aktif</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $availableMotors }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Booking Aktif</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $activeBookings }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Pengguna Aktif</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $totalUsers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
