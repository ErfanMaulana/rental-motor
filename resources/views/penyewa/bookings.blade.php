@extends('layouts.fann')

@section('title', 'Riwayat Pemesanan - Kelola dan pantau semua pemesanan motor Anda')

@section('content')
<!-- Content Header -->
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $bookings->total() }}</h3>
                <p class="text-gray-500 text-sm mt-1">Total Booking</p>
            </div>
            <i class="bi bi-calendar-check text-4xl text-gray-300"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $bookings->where('status', 'pending')->count() }}</h3>
                <p class="text-gray-500 text-sm mt-1">Menunggu</p>
            </div>
            <i class="bi bi-clock text-4xl text-gray-300"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $bookings->where('status', 'active')->count() }}</h3>
                <p class="text-gray-500 text-sm mt-1">Aktif</p>
            </div>
            <i class="bi bi-play-circle text-4xl text-gray-300"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $bookings->where('status', 'completed')->count() }}</h3>
                <p class="text-gray-500 text-sm mt-1">Selesai</p>
            </div>
            <i class="bi bi-check-circle text-4xl text-gray-300"></i>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h5 class="text-lg font-semibold text-gray-900">Daftar Pemesanan</h5>
        <a href="{{ route('penyewa.motors') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="bi bi-plus-circle mr-1"></i>Sewa Motor Baru
        </a>
    </div>
    <div class="p-6">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Sewa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($booking->motor->photo)
                                            <img src="{{ Storage::url($booking->motor->photo) }}" 
                                                 alt="{{ $booking->motor->brand }}"
                                                 class="w-12 h-12 rounded object-cover mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center mr-3">
                                                <i class="bi bi-scooter text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->motor->type_cc }} • {{ $booking->motor->year }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $packageLabels = [
                                            'daily' => 'Harian',
                                            'weekly' => 'Mingguan', 
                                            'monthly' => 'Bulanan'
                                        ];
                                        $packageColors = [
                                            'daily' => 'bg-blue-100 text-blue-800',
                                            'weekly' => 'bg-green-100 text-green-800',
                                            'monthly' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $packageColors[$booking->package_type ?? 'daily'] }}">
                                        {{ $packageLabels[$booking->package_type ?? 'daily'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->start_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">s/d {{ $booking->end_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @php
                                        $days = $booking->start_date->diffInDays($booking->end_date) + 1;
                                    @endphp
                                    {{ $days }} hari
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @switch($booking->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
                                            @break
                                        @case('confirmed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Dikonfirmasi</span>
                                            @break
                                        @case('active')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($booking->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                @click.away="open = false"
                                                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition"
                                                title="Menu Aksi">
                                            <i class="bi bi-three-dots-vertical text-lg"></i>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             style="display: none;"
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                            
                                            <!-- Lihat Detail -->
                                            <a href="{{ route('penyewa.booking.detail', $booking->id) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition rounded-t-lg">
                                                <i class="bi bi-eye mr-2"></i>
                                                Lihat Detail
                                            </a>
                                            
                                            <!-- Batalkan Booking (jika status pending) -->
                                            @if($booking->status === 'pending')
                                                <button type="button"
                                                        onclick="if(confirm('Batalkan pemesanan ini?')) document.getElementById('cancel-form-{{ $booking->id }}').submit();"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition rounded-b-lg">
                                                    <i class="bi bi-x-circle mr-2"></i>
                                                    Batalkan Pesanan
                                                </button>
                                                <form id="cancel-form-{{ $booking->id }}" 
                                                      action="{{ route('penyewa.booking.cancel', $booking->id) }}" 
                                                      method="POST" class="hidden">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-calendar-x text-gray-300 text-6xl"></i>
                <h4 class="mt-4 text-xl font-semibold text-gray-600">Belum ada pemesanan</h4>
                <p class="text-gray-500 mt-2">Mulai sewa motor untuk melihat riwayat pemesanan Anda</p>
                <a href="{{ route('penyewa.motors') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="bi bi-plus-circle mr-1"></i>Sewa Motor Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($bookings->hasPages())
    <div class="flex justify-center mt-6">
        {{ $bookings->links() }}
    </div>
@endif
@endsection
