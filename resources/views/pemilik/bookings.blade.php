@extends('layouts.fann')

@section('title', 'Kelola Pemesanan')

@section('content')
<div class="text-gray-900">
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Kelola Pemesanan</h1>
    <p class="text-gray-600 mt-1">Kelola dan pantau pemesanan motor Anda</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-3xl font-bold text-gray-900">{{ $totalBookings ?? 0 }}</h3>
                <p class="text-gray-500 text-sm mt-1">Total Pemesanan</p>
            </div>
            <i class="bi bi-calendar-check text-5xl text-gray-300"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-3xl font-bold text-green-600">{{ $activeBookings ?? 0 }}</h3>
                <p class="text-gray-500 text-sm mt-1">Sedang Aktif</p>
            </div>
            <i class="bi bi-play-circle text-5xl text-gray-300"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-3xl font-bold text-orange-500">{{ $pendingBookings ?? 0 }}</h3>
                <p class="text-gray-500 text-sm mt-1">Menunggu Konfirmasi</p>
            </div>
            <i class="bi bi-clock text-5xl text-gray-300"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-3xl font-bold text-blue-600">{{ $completedBookings ?? 0 }}</h3>
                <p class="text-gray-500 text-sm mt-1">Selesai</p>
            </div>
            <i class="bi bi-check-circle text-5xl text-gray-300"></i>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-lg shadow-lg p-4 mb-6">
    <form method="GET" action="{{ route('pemilik.bookings') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="status">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
            <div class="flex gap-2">
                <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" type="submit">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('pemilik.bookings') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Daftar Pemesanan Motor</h5>
    </div>
    <div class="overflow-x-auto">
        @if($bookings->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Sewa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-blue-600">#{{ $booking->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full text-white flex items-center justify-center font-semibold mr-3">
                                    {{ substr($booking->renter->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $booking->renter->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->renter->email }}</div>
                                    @if($booking->renter->phone)
                                        <div class="text-xs text-gray-500">{{ $booking->renter->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->motor->plate_number }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">{{ $booking->motor->type_cc }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->start_date->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">s/d {{ $booking->end_date->format('d M Y') }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">{{ $booking->getDurationInDays() }} hari</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-green-600">Rp {{ number_format($booking->price, 0, ',', '.') }}</div>
                            @if($booking->payment)
                                <div class="text-xs text-gray-600">{{ ucfirst($booking->payment->method) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($booking->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Dikonfirmasi</span>
                            @elseif($booking->status === 'active')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sedang Berlangsung</span>
                            @elseif($booking->status === 'completed')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Selesai</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    Aksi <i class="bi bi-chevron-down ml-1"></i>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     style="display: none;"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-10">
                                    <a href="#" onclick="viewBookingDetail({{ $booking->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                        <i class="bi bi-eye mr-2"></i>Detail
                                    </a>
                                    @if($booking->status === 'pending')
                                        <a href="#" onclick="confirmBooking({{ $booking->id }})" class="block px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                            <i class="bi bi-check-circle mr-2"></i>Terima
                                        </a>
                                        <a href="#" onclick="cancelBooking({{ $booking->id }})" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg">
                                            <i class="bi bi-x-circle mr-2"></i>Tolak
                                        </a>
                                    @endif
                                    @if($booking->status === 'confirmed')
                                        <a href="#" onclick="activateBooking({{ $booking->id }})" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-b-lg">
                                            <i class="bi bi-play-circle mr-2"></i>Mulai Sewa
                                        </a>
                                    @endif
                                    @if($booking->status === 'active')
                                        <a href="#" onclick="completeBooking({{ $booking->id }})" class="block px-4 py-2 text-sm text-purple-600 hover:bg-purple-50 rounded-b-lg">
                                            <i class="bi bi-check-circle-fill mr-2"></i>Selesaikan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-12">
                <i class="bi bi-calendar-x text-gray-300 text-6xl"></i>
                <h4 class="mt-4 text-xl font-semibold text-gray-600">Tidak ada pemesanan ditemukan</h4>
                <p class="text-gray-500 mt-2">Belum ada pemesanan untuk motor Anda atau coba ubah filter pencarian</p>
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

<!-- Booking Detail Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-booking-detail.window="open = true"
     @keydown.escape.window="open = false"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
             @click="open = false"></div>

        <!-- Modal panel -->
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-3xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-blue-600 text-white">
                <h3 class="text-lg font-medium">Detail Pemesanan</h3>
                <button @click="open = false" class="text-white hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div id="bookingDetailContent" class="px-6 py-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewBookingDetail(bookingId) {
    // Load booking detail via AJAX
    fetch(`/pemilik/booking/${bookingId}/detail`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('bookingDetailContent').innerHTML = html;
            window.dispatchEvent(new CustomEvent('open-booking-detail'));
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail pemesanan');
        });
}

function confirmBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin menerima pemesanan ini?')) {
        fetch(`/pemilik/booking/${bookingId}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal mengkonfirmasi pemesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function cancelBooking(bookingId) {
    const reason = prompt('Masukkan alasan penolakan:');
    if (reason && reason.trim() !== '') {
        fetch(`/pemilik/booking/${bookingId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal membatalkan pemesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function activateBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin memulai masa sewa ini?')) {
        fetch(`/pemilik/booking/${bookingId}/activate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal memulai masa sewa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function completeBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin menyelesaikan masa sewa ini?')) {
        fetch(`/pemilik/booking/${bookingId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menyelesaikan masa sewa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
</div>
@endsection