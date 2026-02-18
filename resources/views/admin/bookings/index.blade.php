@extends('layouts.fann')

@section('title', 'Kelola Pemesanan')

@section('content')

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pemesanan</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $bookings->total() }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Menunggu</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Dikonfirmasi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $stats['confirmed'] ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Berlangsung</p>
            <p class="text-2xl font-semibold text-green-600">{{ $stats['ongoing'] ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.bookings') }}" id="filterForm" class="flex flex-wrap gap-3">
        <select class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('status') ? 'bg-blue-50 border-blue-300' : '' }}" name="status" id="statusFilter">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Berlangsung</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <input type="date" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('date_from') ? 'bg-blue-50 border-blue-300' : '' }}" name="date_from" id="dateFromFilter" value="{{ request('date_from') }}">
        <input type="date" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('date_to') ? 'bg-blue-50 border-blue-300' : '' }}" name="date_to" id="dateToFilter" value="{{ request('date_to') }}">
        <input type="text" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('search') ? 'bg-blue-50 border-blue-300' : '' }}" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Nama penyewa, motor, atau plat nomor...">
        <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" type="submit">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('admin.bookings') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" title="Reset">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </form>
    
    @if(request('status') || request('date_from') || request('date_to') || request('search'))
    <div class="mt-3 flex items-center gap-2 text-xs">
        <span class="text-gray-600">Filter aktif:</span>
        @if(request('status'))
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                Status: {{ ucfirst(request('status')) }}
            </span>
        @endif
        @if(request('date_from'))
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                Dari: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}
            </span>
        @endif
        @if(request('date_to'))
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                Sampai: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}
            </span>
        @endif
        @if(request('search'))
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                Pencarian: "{{ request('search') }}"
            </span>
        @endif
    </div>
    @endif
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
                    <div class="text-xs text-gray-500">{{ $booking->motor->plate_number }} - {{ $booking->motor->type_cc }}</div>
                </td>
                <td class="px-4 py-3 text-sm">
                    <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $booking->getDurationInDays() }} hari</span>
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
                    @if($booking->status == 'pending')
                        <span class="px-2.5 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded">
                            Menunggu
                        </span>
                    @elseif($booking->status == 'confirmed')
                        <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">
                            Dikonfirmasi
                        </span>
                    @elseif($booking->status == 'ongoing' || $booking->status == 'active')
                        <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">
                            Berlangsung
                        </span>
                    @elseif($booking->status == 'completed')
                        <span class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded">
                            Selesai
                        </span>
                    @elseif($booking->status == 'cancelled')
                        <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">
                            Dibatalkan
                        </span>
                    @else
                        <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                            {{ $booking->status }}
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="relative dropdown-container">
                        <button onclick="toggleDropdown(this, event)" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Menu Aksi">
                            <i class="bi bi-three-dots-vertical text-lg"></i>
                        </button>
                        <div class="dropdown-menu hidden absolute right-0 mt-1 w-52 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                <button onclick="viewBooking({{ $booking->id }})" 
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-3">
                                    <i class="bi bi-eye text-blue-600 text-base"></i>
                                    <span>Lihat Detail</span>
                                </button>
                                
                                @if($booking->status === 'pending')
                                <div class="border-t border-gray-100"></div>
                                <button onclick="confirmBooking({{ $booking->id }})" 
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-green-50 flex items-center gap-3">
                                    <i class="bi bi-check-circle text-green-600 text-base"></i>
                                    <span>Konfirmasi Booking</span>
                                </button>
                                <button onclick="cancelBooking({{ $booking->id }})" 
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-red-50 flex items-center gap-3">
                                    <i class="bi bi-x-circle text-red-600 text-base"></i>
                                    <span>Batalkan Booking</span>
                                </button>
                                
                                @elseif($booking->status === 'confirmed')
                                <div class="border-t border-gray-100"></div>
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $startDate = \Carbon\Carbon::parse($booking->start_date);
                                    $canActivate = $startDate->lte($today);
                                @endphp
                                @if($canActivate)
                                <button onclick="startBooking({{ $booking->id }})" 
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-3">
                                    <i class="bi bi-play-circle text-blue-600 text-base"></i>
                                    <span>Aktifkan Rental</span>
                                </button>
                                @else
                                <button disabled
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-400 bg-gray-50 cursor-not-allowed flex items-center gap-3"
                                        title="Rental hanya dapat diaktifkan pada tanggal {{ $startDate->format('d/m/Y') }}">
                                    <i class="bi bi-play-circle text-gray-400 text-base"></i>
                                    <span>Aktifkan Rental</span>
                                </button>
                                <div class="px-4 py-2 text-xs text-gray-500 bg-yellow-50 border-t border-yellow-100">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Rental dimulai {{ $startDate->format('d/m/Y') }}
                                </div>
                                @endif
                                
                                @elseif($booking->status === 'ongoing' || $booking->status === 'active')
                                <div class="border-t border-gray-100"></div>
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $endDate = \Carbon\Carbon::parse($booking->end_date);
                                    $canComplete = $endDate->lte($today);
                                    $remainingDays = $today->diffInDays($endDate, false);
                                @endphp
                                @if($canComplete)
                                <button onclick="completeBooking({{ $booking->id }})" 
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-purple-50 flex items-center gap-3">
                                    <i class="bi bi-check2-circle text-purple-600 text-base"></i>
                                    <span>Selesaikan Rental</span>
                                </button>
                                @else
                                <button disabled
                                        class="w-full px-4 py-2.5 text-left text-sm text-gray-400 bg-gray-50 cursor-not-allowed flex items-center gap-3"
                                        title="Rental hanya dapat diselesaikan pada tanggal {{ $endDate->format('d/m/Y') }}">
                                    <i class="bi bi-check2-circle text-gray-400 text-base"></i>
                                    <span>Selesaikan Rental</span>
                                </button>
                                <div class="px-4 py-2 text-xs text-gray-500 bg-blue-50 border-t border-blue-100">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Rental berakhir {{ $endDate->format('d/m/Y') }} ({{ abs($remainingDays) }} hari lagi)
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
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

<!-- Modal Detail Booking -->
<div id="bookingDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeBookingModal()">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Pemesanan</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
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
// Handle filter auto-submit
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const statusFilter = document.getElementById('statusFilter');
    const dateFromFilter = document.getElementById('dateFromFilter');
    const dateToFilter = document.getElementById('dateToFilter');
    const searchInput = document.getElementById('searchInput');
    
    // Auto-submit when status filter changes
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            console.log('Status filter changed to:', this.value);
            filterForm.submit();
        });
    }
    
    // Auto-submit when date filters change
    if (dateFromFilter) {
        dateFromFilter.addEventListener('change', function() {
            console.log('Date from filter changed to:', this.value);
            filterForm.submit();
        });
    }
    
    if (dateToFilter) {
        dateToFilter.addEventListener('change', function() {
            console.log('Date to filter changed to:', this.value);
            filterForm.submit();
        });
    }
    
    // Enable Enter key to submit search
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                console.log('Search submitted:', this.value);
                filterForm.submit();
            }
        });
    }
    
    // Log current filters on page load
    console.log('Current bookings filters:', {
        status: statusFilter ? statusFilter.value : null,
        dateFrom: dateFromFilter ? dateFromFilter.value : null,
        dateTo: dateToFilter ? dateToFilter.value : null,
        search: searchInput ? searchInput.value : null
    });
});

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

function viewBooking(bookingId) {
    const modal = document.getElementById('bookingDetailModal');
    const content = document.getElementById('bookingDetailContent');
    
    // Show modal with loading state
    modal.classList.remove('hidden');
    content.innerHTML = `
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
    `;
    
    // Fetch booking details
    fetch(`/admin/bookings/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.message || 'Gagal memuat detail booking');
            }
            
            const booking = data;
            const startDate = new Date(booking.start_date);
            const endDate = new Date(booking.end_date);
            
            // Calculate duration in days
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            const duration = booking.duration_days || diffDays;
            
            // Format currency
            const formatCurrency = (amount) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            };
            
            // Format date
            const formatDate = (date) => {
                return new Intl.DateTimeFormat('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).format(date);
            };
            
            // Status badge HTML
            let statusBadge = '';
            if (booking.status === 'pending') {
                statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded">Menunggu</span>';
            } else if (booking.status === 'confirmed') {
                statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">Dikonfirmasi</span>';
            } else if (booking.status === 'active' || booking.status === 'ongoing') {
                statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">Berlangsung</span>';
            } else if (booking.status === 'completed') {
                statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded">Selesai</span>';
            } else if (booking.status === 'cancelled') {
                statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">Dibatalkan</span>';
            }
            
            // Payment method badge HTML
            let paymentBadge = '-';
            if (booking.payment_method) {
                const methodMap = {
                    'dana': '<span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded"><i class="bi bi-wallet2 mr-1"></i>DANA</span>',
                    'gopay': '<span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded"><i class="bi bi-wallet2 mr-1"></i>GoPay</span>',
                    'bank': '<span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded"><i class="bi bi-bank mr-1"></i>Transfer Bank</span>',
                    'shopeepay': '<span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded"><i class="bi bi-wallet2 mr-1"></i>ShopeePay</span>'
                };
                paymentBadge = methodMap[booking.payment_method] || booking.payment_method;
            }
            
            content.innerHTML = `
                <div class="space-y-4">
                    <!-- Status Badge -->
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-base font-bold text-gray-900">${booking.booking_code}</h4>
                            <p class="text-xs text-gray-500">${formatDate(new Date(booking.created_at))}</p>
                        </div>
                        ${statusBadge}
                    </div>
                    
                    <!-- Informasi Penyewa -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="bi bi-person-circle mr-1.5 text-sm text-blue-600"></i>Informasi Penyewa
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium text-gray-900">${booking.renter.name}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900 text-xs">${booking.renter.email}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">No. Telepon:</span>
                                <span class="font-medium text-gray-900">${booking.renter.phone || '-'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Motor -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="bi bi-bicycle mr-1.5 text-sm text-blue-600"></i>Informasi Motor
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Brand & Model:</span>
                                <span class="font-medium text-gray-900">${booking.motor.brand} ${booking.motor.model}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Plat Nomor:</span>
                                <span class="font-medium text-gray-900">${booking.motor.plate_number}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Kapasitas:</span>
                                <span class="font-medium text-gray-900">${booking.motor.type_cc || '-'}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pemilik:</span>
                                <span class="font-medium text-gray-900">${booking.motor.owner?.name || '-'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Sewa -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="bi bi-calendar-check mr-1.5 text-sm text-blue-600"></i>Detail Sewa
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Mulai:</span>
                                <span class="font-medium text-gray-900">${formatDate(startDate)}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Selesai:</span>
                                <span class="font-medium text-gray-900">${formatDate(endDate)}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Durasi:</span>
                                <span class="font-medium text-gray-900">${duration} hari</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Metode Pembayaran:</span>
                                <span class="font-medium text-gray-900">${paymentBadge}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rincian Biaya -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="bi bi-cash-stack mr-1.5 text-sm text-blue-600"></i>Rincian Biaya
                        </h4>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-900">Total Biaya</span>
                                <span class="text-lg font-bold text-blue-600">${formatCurrency(booking.price)}</span>
                            </div>
                        </div>
                        ${booking.notes ? `
                        <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs text-gray-600 mb-1 font-semibold">Catatan:</p>
                            <p class="text-xs text-gray-700">${booking.notes}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <!-- Action Button -->
                    <div class="flex justify-end pt-2">
                        <button onclick="closeBookingModal()" class="px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="bi bi-exclamation-circle text-4xl text-red-500 mb-3"></i>
                    <p class="text-gray-900 font-medium mb-2">Gagal Memuat Detail</p>
                    <p class="text-sm text-gray-500 mb-4">${error.message}</p>
                    <button onclick="closeBookingModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tutup
                    </button>
                </div>
            `;
        });
}

function closeBookingModal() {
    const modal = document.getElementById('bookingDetailModal');
    modal.classList.add('hidden');
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('bookingDetailModal');
        if (!modal.classList.contains('hidden')) {
            closeBookingModal();
        }
    }
});

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
        updateBookingStatus(bookingId, 'active');
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
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || 'Gagal mengupdate status pemesanan');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal mengupdate status pemesanan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Terjadi kesalahan saat mengupdate status');
    });
}
</script>
@endpush