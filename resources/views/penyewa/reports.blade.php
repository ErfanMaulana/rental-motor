@extends('layouts.fann')

@section('title', 'Laporan Rental - Ringkasan aktivitas rental dan rating motor')

@section('content')
<!-- Content Header -->
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="text-blue-600 mb-3">
            <i class="bi bi-calendar-check text-5xl"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</h3>
        <p class="text-gray-500 mt-2">Total Booking</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="text-blue-600 mb-3">
            <i class="bi bi-check-circle text-5xl"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $completedBookings }}</h3>
        <p class="text-gray-500 mt-2">Selesai</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="text-blue-600 mb-3">
            <i class="bi bi-clock text-5xl"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $activeBookings }}</h3>
        <p class="text-gray-500 mt-2">Aktif</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <div class="text-blue-600 mb-3">
            <i class="bi bi-currency-dollar text-5xl"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalSpending, 0, ',', '.') }}</h3>
        <p class="text-gray-500 mt-2">Total Pengeluaran</p>
    </div>
</div>

<!-- Recent Bookings & Ratings Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h5 class="text-lg font-semibold text-gray-900">
                    <i class="bi bi-calendar-check mr-2"></i>Booking Terbaru
                </h5>
                <a href="{{ route('penyewa.bookings') }}" class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="p-6">
            @forelse($recentBookings as $booking)
                <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:shadow-sm transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <h6 class="font-bold text-gray-900 mb-1">
                                {{ $booking->motor ? $booking->motor->brand . ' ' . $booking->motor->model : 'Motor Tidak Ditemukan' }}
                            </h6>
                            @if($booking->motor)
                                <p class="text-gray-500 text-sm mb-1">{{ $booking->motor->plate_number }}</p>
                            @endif
                            <p class="text-gray-500 text-sm mb-1">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                            </p>
                            <p class="text-green-600 font-bold">Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</p>
                        </div>
                        <div>
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'active' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="bi bi-calendar-x text-gray-300 text-6xl"></i>
                    <p class="text-gray-500 mt-3">Belum ada booking</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Ratings Given -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900">
                <i class="bi bi-star mr-2"></i>Rating yang Diberikan
            </h5>
        </div>
        <div class="p-6">
            @forelse($ratingsGiven as $rating)
                <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:shadow-sm transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <h6 class="font-bold text-gray-900 mb-1">
                                {{ $rating->motor ? $rating->motor->brand . ' ' . $rating->motor->model : 'Motor Tidak Ditemukan' }}
                            </h6>
                            <div class="flex items-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating->rating ? '-fill text-yellow-400' : ' text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-gray-500 text-sm">({{ $rating->rating }}/5)</span>
                            </div>
                            @if($rating->review)
                                <p class="text-gray-600 text-sm mb-2">{{ Str::limit($rating->review, 100) }}</p>
                            @endif
                            <p class="text-gray-500 text-sm">{{ $rating->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="bi bi-star text-gray-300 text-6xl"></i>
                    <p class="text-gray-500 mt-3">Belum ada rating yang diberikan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">
            <i class="bi bi-download mr-2"></i>Export Laporan
        </h5>
    </div>
    <div class="p-6">
        <div class="flex gap-3">
            <button onclick="exportReport('pdf')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="bi bi-file-pdf mr-2"></i>Export PDF
            </button>
            <button onclick="exportReport('excel')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="bi bi-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportReport(format) {
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Exporting...';
    button.disabled = true;
    
    fetch(`{{ route('penyewa.reports.export') }}?format=${format}`)
        .then(response => response.json())
        .then(data => {
            console.log('Export data:', data);
            alert('Export berhasil! Data tersedia di console browser.');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi error saat export');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
}
</script>
@endpush
@endsection