@extends('layouts.fann')

@section('title', 'Kelola Pemesanan')

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
            <p class="text-2xl font-semibold text-gray-900">{{ $bookings->total() }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Pending</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Dikonfirmasi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $stats['confirmed'] ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Sedang Aktif</p>
            <p class="text-2xl font-semibold text-green-600">{{ $stats['ongoing'] ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-wrap gap-3">
        <select class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="status">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Sedang Berlangsung</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <input type="date" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="date_from" value="{{ request('date_from') }}">
        <input type="date" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="date_to" value="{{ request('date_to') }}">
        <input type="text" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="search" value="{{ request('search') }}" placeholder="Nama penyewa, motor, atau plat nomor...">
        <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" type="submit">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('admin.bookings') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" title="Reset">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </form>
</div>
                                <div>
                                    <h4>{{ $stats['ongoing'] ?? 0 }}</h4>
                                    <p class="mb-0">Berlangsung</p>
                                </div>
<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Kode Booking</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Penyewa</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Motor</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Tanggal Sewa</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Durasi</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total Harga</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Metode Pembayaran</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm">
                    <div class="font-medium text-gray-900">{{ $booking->booking_code }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td class="px-4 py-3 text-sm">
                    <div class="font-medium text-gray-900">{{ $booking->renter->name }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->renter->phone }}</div>
                </td>
                <td class="px-4 py-3 text-sm">
                    <div class="font-medium text-gray-900">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->motor->plate_number }} - {{ $booking->motor->cc }}cc</div>
                </td>
                <td class="px-4 py-3 text-sm">
                    <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $booking->duration }} hari</span>
                </td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                    Rp {{ number_format($booking->price, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3">
                    @if($booking->payment_method)
                        @if($booking->payment_method === 'dana')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                <i class="bi bi-wallet2 mr-1"></i>DANA
                            </span>
                        @elseif($booking->payment_method === 'gopay')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                                <i class="bi bi-wallet2 mr-1"></i>GoPay
                            </span>
                        @elseif($booking->payment_method === 'bank')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded">
                                <i class="bi bi-bank mr-1"></i>Transfer Bank
                            </span>
                        @elseif($booking->payment_method === 'shopeepay')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded">
                                <i class="bi bi-wallet2 mr-1"></i>ShopeePay
                            </span>
                        @endif
                    @else
                        <span class="text-xs text-gray-400 italic">-</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded
                        @if($booking->status == 'pending') bg-yellow-100 text-yellow-700
                        @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-700
                        @elseif($booking->status == 'ongoing') bg-green-100 text-green-700
                        @elseif($booking->status == 'completed') bg-gray-100 text-gray-700
                        @else bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <button onclick="viewBooking({{ $booking->id }})" 
                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" 
                                title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </button>
                        @if($booking->status == 'pending')
                        <button onclick="confirmBooking({{ $booking->id }})" 
                                class="p-1.5 text-green-600 hover:bg-green-50 rounded" 
                                title="Konfirmasi">
                            <i class="bi bi-check"></i>
                        </button>
                        <button onclick="cancelBooking({{ $booking->id }})" 
                                class="p-1.5 text-red-600 hover:bg-red-50 rounded" 
                                title="Batalkan">
                            <i class="bi bi-x"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                    <i class="bi bi-inbox text-4xl mb-2 block"></i>
                    Tidak ada pemesanan ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($bookings->hasPages())
<div class="mt-4">
    {{ $bookings->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
function viewBooking(bookingId) {
    // Implement view booking with Tailwind modal or SweetAlert
    fetch(`/admin/bookings/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            alert(`Detail Pemesanan:\nKode: ${data.booking_code}\nPenyewa: ${data.renter.name}\nMotor: ${data.motor.brand} ${data.motor.model}\nTotal: Rp ${new Intl.NumberFormat('id-ID').format(data.price)}`);
        });
}

function confirmBooking(bookingId) {
    if (confirm('Konfirmasi pemesanan ini?')) {
        updateBookingStatus(bookingId, 'confirmed');
    }
}

function cancelBooking(bookingId) {
    if (confirm('Batalkan pemesanan ini?')) {
        updateBookingStatus(bookingId, 'cancelled');
    }
}

function startBooking(bookingId) {
    if (confirm('Mulai proses penyewaan?')) {
        updateBookingStatus(bookingId, 'ongoing');
    }
}

function completeBooking(bookingId) {
    if (confirm('Selesaikan penyewaan ini?')) {
        updateBookingStatus(bookingId, 'completed');
    }
}

function updateBookingStatus(bookingId, status) {
    fetch(`/admin/bookings/${bookingId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupdate status pemesanan');
        }
    });
}
</script>
@endpush