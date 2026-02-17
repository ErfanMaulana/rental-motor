@extends('layouts.fann')

@section('title', 'Laporan Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
        Kelola Pemesanan
    </h1>
    <p class="text-sm text-gray-500 mt-1">Manajemen pemesanan motor dalam sistem rental</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode Pembayaran</th>
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
                                        <i class="bi bi-bank mr-1"></i>Bank Transfer
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
                            <div class="relative dropdown-container">
                                <button onclick="toggleDropdown(this, event)" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Menu Aksi">
                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute right-0 mt-1 w-52 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                    <div class="py-1">
                                        <button onclick="showBookingDetail({{ $booking->id }})" 
                                                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-3">
                                            <i class="bi bi-eye text-blue-600 text-base"></i>
                                            <span>Lihat Detail</span>
                                        </button>
                                        
                                        @if($booking->status === 'pending')
                                        <div class="border-t border-gray-100"></div>
                                        <button onclick="updateBookingStatus({{ $booking->id }}, 'confirmed')" 
                                                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-green-50 flex items-center gap-3">
                                            <i class="bi bi-check-circle text-green-600 text-base"></i>
                                            <span>Konfirmasi Booking</span>
                                        </button>
                                        <button onclick="updateBookingStatus({{ $booking->id }}, 'cancelled')" 
                                                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-red-50 flex items-center gap-3">
                                            <i class="bi bi-x-circle text-red-600 text-base"></i>
                                            <span>Batalkan Booking</span>
                                        </button>
                                        
                                        @elseif($booking->status === 'confirmed')
                                        <div class="border-t border-gray-100"></div>
                                        <button onclick="updateBookingStatus({{ $booking->id }}, 'active')" 
                                                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-3">
                                            <i class="bi bi-play-circle text-blue-600 text-base"></i>
                                            <span>Aktifkan Rental</span>
                                        </button>
                                        
                                        @elseif($booking->status === 'active')
                                        <div class="border-t border-gray-100"></div>
                                        <button onclick="updateBookingStatus({{ $booking->id }}, 'completed')" 
                                                class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-purple-50 flex items-center gap-3">
                                            <i class="bi bi-check2-circle text-purple-600 text-base"></i>
                                            <span>Selesaikan Rental</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
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

<!-- Modal Detail Booking -->
<div id="bookingDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeBookingDetailModal()">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Booking</h3>
            <button onclick="closeBookingDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div id="bookingDetailContent" class="p-4">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle dropdown menu
function toggleDropdown(button, event) {
    event.stopPropagation();
    const container = button.closest('.dropdown-container');
    const menu = container.querySelector('.dropdown-menu');
    const allMenus = document.querySelectorAll('.dropdown-menu');
    
    // Close all other dropdowns
    allMenus.forEach(m => {
        if (m !== menu) {
            m.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    menu.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-container')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

function showBookingDetail(bookingId) {
    const modal = document.getElementById('bookingDetailModal');
    const content = document.getElementById('bookingDetailContent');
    
    // Show modal with loading
    modal.classList.remove('hidden');
    content.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
    `;
    
    // Fetch booking detail
    fetch(`/admin/bookings/${bookingId}/detail`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const booking = data.booking;
                content.innerHTML = `
                    <div class="space-y-4">
                        <!-- Info Booking -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-calendar-check mr-1.5 text-sm"></i>Informasi Booking
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Kode Booking</span>
                                    <span class="font-medium text-blue-600">#${booking.id}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Tanggal Booking</span>
                                    <span class="font-medium text-gray-900">${booking.formatted_created_at}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Periode Sewa</span>
                                    <span class="font-medium text-gray-900 text-right ml-2">${booking.formatted_rental_period}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Durasi</span>
                                    <span class="font-medium text-gray-900">${booking.duration} hari</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Total Biaya</span>
                                    <span class="font-semibold text-green-600">Rp ${booking.formatted_total_cost}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Status</span>
                                    <span>${booking.status_badge}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Penyewa -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-person mr-1.5 text-sm"></i>Informasi Penyewa
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Nama</span>
                                    <span class="font-medium text-gray-900">${booking.renter.name}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Email</span>
                                    <span class="font-medium text-gray-900 text-right truncate ml-2">${booking.renter.email}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Telepon</span>
                                    <span class="font-medium text-gray-900">${booking.renter.phone || '-'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Motor -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-bicycle mr-1.5 text-sm"></i>Informasi Motor
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Motor</span>
                                    <span class="font-medium text-gray-900">${booking.motor.brand} ${booking.motor.model}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Tipe</span>
                                    <span class="font-medium text-gray-900">${booking.motor.type_cc}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Plat Nomor</span>
                                    <span class="font-medium text-gray-900">${booking.motor.plate_number}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Pemilik</span>
                                    <span class="font-medium text-gray-900">${booking.motor.owner.name}</span>
                                </div>
                            </div>
                        </div>

                        ${booking.payment_method ? `
                        <!-- Info Pembayaran -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-credit-card mr-1.5 text-sm"></i>Informasi Pembayaran
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Metode</span>
                                    <span class="font-medium text-gray-900">${booking.formatted_payment_method}</span>
                                </div>
                                ${booking.payment_status ? `
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Status Pembayaran</span>
                                    <span class="font-medium text-gray-900">${booking.payment_status}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        ` : ''}

                        ${booking.notes ? `
                        <!-- Catatan -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-sticky mr-1.5 text-sm"></i>Catatan
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-900">${booking.notes}</p>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Action Buttons -->
                        ${booking.status === 'pending' ? `
                        <div class="flex gap-2 pt-3 border-t border-gray-200">
                            <button onclick="closeBookingDetailModal(); updateBookingStatus(${booking.id}, 'confirmed')" class="flex-1 px-3 py-2 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center gap-1.5">
                                <i class="bi bi-check-circle"></i>
                                Konfirmasi
                            </button>
                            <button onclick="closeBookingDetailModal(); updateBookingStatus(${booking.id}, 'cancelled')" class="flex-1 px-3 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center gap-1.5">
                                <i class="bi bi-x-circle"></i>
                                Batalkan
                            </button>
                        </div>
                        ` : booking.status === 'confirmed' ? `
                        <div class="pt-3 border-t border-gray-200">
                            <button onclick="closeBookingDetailModal(); updateBookingStatus(${booking.id}, 'active')" class="w-full px-3 py-2 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center gap-1.5">
                                <i class="bi bi-play-circle"></i>
                                Aktifkan Rental
                            </button>
                        </div>
                        ` : booking.status === 'active' ? `
                        <div class="pt-3 border-t border-gray-200">
                            <button onclick="closeBookingDetailModal(); updateBookingStatus(${booking.id}, 'completed')" class="w-full px-3 py-2 text-xs bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center justify-center gap-1.5">
                                <i class="bi bi-check2-circle"></i>
                                Selesaikan Rental
                            </button>
                        </div>
                        ` : ''}
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="bi bi-exclamation-triangle text-4xl text-red-500 mb-2 block"></i>
                        <p class="text-gray-700">Gagal memuat detail booking</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="bi bi-exclamation-triangle text-4xl text-red-500 mb-2 block"></i>
                    <p class="text-gray-700">Terjadi kesalahan saat memuat data</p>
                </div>
            `;
        });
}

function closeBookingDetailModal() {
    document.getElementById('bookingDetailModal').classList.add('hidden');
}

function updateBookingStatus(bookingId, status) {
    const statusMessages = {
        'confirmed': 'konfirmasi',
        'cancelled': 'batalkan',
        'active': 'aktifkan',
        'completed': 'selesaikan'
    };
    
    if (!confirm(`Apakah Anda yakin ingin ${statusMessages[status]} booking ini?`)) {
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/bookings/${bookingId}/status`;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PATCH';
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = status;
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    form.appendChild(statusInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush