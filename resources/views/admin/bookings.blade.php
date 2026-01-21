@extends('layouts.fann')

@section('title', 'Laporan Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
        <i class="bi bi-calendar-check text-blue-600 mr-3"></i>
        Kelola Pemesanan
    </h1>
    <p class="text-sm text-gray-500 mt-1 ml-11">Manajemen pemesanan motor dalam sistem rental</p>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pemesanan</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $totalBookings ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Menunggu Konfirmasi</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $pendingBookings ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Sedang Aktif</p>
            <p class="text-2xl font-semibold text-green-600">{{ $activeBookings ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pendapatan</p>
            <p class="text-2xl font-semibold text-blue-600">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form id="bookingFilterForm" method="GET" action="{{ route('admin.bookings') }}" class="flex flex-wrap gap-3">
        <select onchange="document.getElementById('bookingFilterForm').submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="status">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="start_date" value="{{ request('start_date') }}" placeholder="Dari Tanggal">
        <input type="date" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="end_date" value="{{ request('end_date') }}" placeholder="Sampai Tanggal">
        <input type="text" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="search" value="{{ request('search') }}" placeholder="Nama penyewa, motor, atau plat nomor...">
        <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" type="submit">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('admin.bookings') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" title="Reset">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </form>
</div>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900">Daftar Pemesanan</h3>
        <button class="px-3 py-1.5 text-xs border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
            <i class="bi bi-download mr-1"></i>Export Bookings
        </button>
    </div>
    @if($bookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Sewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-blue-600">#{{ $booking->id }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->renter->name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->renter->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->motor->brand }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->motor->plate_number }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div>{{ $booking->start_date->format('d M Y') }}</div>
                            <div class="text-xs">s/d {{ $booking->end_date->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">{{ $booking->duration }} hari</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($booking->status === 'pending')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Pending</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">Confirmed</span>
                            @elseif($booking->status === 'active')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Active</span>
                            @elseif($booking->status === 'completed')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">Completed</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.booking.detail', $booking->id) }}" class="text-sm text-blue-600 hover:text-blue-700">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-16">
            <i class="bi bi-calendar-x text-gray-300" style="font-size: 5rem;"></i>
            <h4 class="mt-4 text-lg font-medium text-gray-700">Tidak ada pemesanan ditemukan</h4>
            <p class="text-sm text-gray-500 mt-1">Belum ada transaksi atau coba ubah filter pencarian</p>
        </div>
    @endif
</div>

@if($bookings->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $bookings->appends(request()->except('page'))->links() }}
    </div>
@endif

@endsection