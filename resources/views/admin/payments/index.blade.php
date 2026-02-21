@extends('layouts.fann')

@section('title', 'Verifikasi Pembayaran')

@section('content')

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pembayaran</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $summary['total_payments'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Menunggu Verifikasi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $summary['unverified_payments'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Sudah Diverifikasi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $summary['verified_payments'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Nilai Pending</p>
            <p class="text-2xl font-semibold text-blue-600">Rp {{ number_format($summary['pending_amount'], 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.payments') }}" id="filterForm" class="flex flex-wrap gap-3">
        <select name="status" id="statusFilter" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('status') ? 'bg-blue-50 border-blue-300' : '' }}">
            <option value="">Semua Status</option>
            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Diverifikasi</option>
            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Sudah Diverifikasi</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
        </select>
        <select name="payment_method" id="methodFilter" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('payment_method') ? 'bg-blue-50 border-blue-300' : '' }}">
            <option value="">Semua Metode</option>
            <option value="dana" {{ request('payment_method') == 'dana' ? 'selected' : '' }}>DANA</option>
            <option value="gopay" {{ request('payment_method') == 'gopay' ? 'selected' : '' }}>GoPay</option>
            <option value="shopeepay" {{ request('payment_method') == 'shopeepay' ? 'selected' : '' }}>ShopeePay</option>
            <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Transfer Bank</option>
        </select>
        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 {{ request('search') ? 'bg-blue-50 border-blue-300' : '' }}" placeholder="Nama penyewa, email, atau ID booking...">
        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('admin.payments') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" title="Reset">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </form>
    
    @if(request('status') || request('payment_method') || request('search'))
    <div class="mt-3 flex items-center gap-2 text-xs">
        <span class="text-gray-600">Filter aktif:</span>
        @if(request('status'))
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                Status: {{ ucfirst(request('status')) }}
            </span>
        @endif
        @if(request('payment_method'))
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                Metode: {{ strtoupper(request('payment_method')) }}
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
    @if($payments->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Penyewa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Motor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Booking</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Metode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $payment->id }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $payment->booking->renter->name }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->booking->renter->email }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $payment->booking->motor->brand }} {{ $payment->booking->motor->model }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->booking->motor->type_cc }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="text-blue-600 hover:underline cursor-pointer">#{{ $payment->booking->id }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-600">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($payment->payment_method)
                            @if($payment->payment_method === 'dana')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    <i class="bi bi-wallet2 mr-1"></i>DANA
                                </span>
                            @elseif($payment->payment_method === 'gopay')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                                    <i class="bi bi-wallet2 mr-1"></i>GoPay
                                </span>
                            @elseif($payment->payment_method === 'bank')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded">
                                    <i class="bi bi-bank mr-1"></i>Transfer Bank
                                </span>
                            @elseif($payment->payment_method === 'shopeepay')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded">
                                    <i class="bi bi-wallet2 mr-1"></i>ShopeePay
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $payment->formatted_payment_method }}</span>
                            @endif
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $payment->formatted_payment_method }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($payment->verified_at)
                            @if($payment->status === 'paid')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded inline-flex items-center">
                                    <i class="bi bi-check-circle mr-1"></i>Diverifikasi
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded inline-flex items-center">
                                    <i class="bi bi-x-circle mr-1"></i>Ditolak
                                </span>
                            @endif
                        @else
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded inline-flex items-center">
                                <i class="bi bi-clock mr-1"></i>Menunggu
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="text-gray-900">{{ $payment->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-4 py-3" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded" title="Menu Aksi">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10"
                                 style="display: none;">
                                <button onclick="showPaymentDetail({{ $payment->id }})" 
                                        class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <i class="bi bi-eye text-blue-600"></i>
                                    Lihat Detail
                                </button>
                                @if($payment->proof_image_url)
                                <a href="{{ $payment->proof_image_url }}" target="_blank"
                                   class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <i class="bi bi-image text-purple-600"></i>
                                    Lihat Bukti Transfer
                                </a>
                                @endif
                                @if(!$payment->verified_at)
                                <div class="border-t border-gray-200"></div>
                                <button onclick="verifyPayment({{ $payment->id }})" 
                                        class="w-full px-4 py-2 text-left text-sm text-green-700 hover:bg-green-50 flex items-center gap-2">
                                    <i class="bi bi-check-circle text-green-600"></i>
                                    Verifikasi Pembayaran
                                </button>
                                <button onclick="rejectPayment({{ $payment->id }})" 
                                        class="w-full px-4 py-2 text-left text-sm text-red-700 hover:bg-red-50 flex items-center gap-2">
                                    <i class="bi bi-x-circle text-red-600"></i>
                                    Tolak Pembayaran
                                </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($payments->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $payments->withQueryString()->links() }}
        </div>
        @endif
    @else
        <div class="px-4 py-12 text-center">
            <i class="bi bi-inbox text-4xl text-gray-400 mb-2 block"></i>
            <h5 class="text-gray-900 font-medium mb-1">Tidak ada pembayaran ditemukan</h5>
            <p class="text-sm text-gray-500">Belum ada pembayaran yang perlu diverifikasi.</p>
        </div>
    @endif
</div>

<!-- Modal Detail Pembayaran -->
<div id="paymentDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closePaymentDetailModal()">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Pembayaran</h3>
            <button onclick="closePaymentDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div id="paymentDetailContent" class="p-4">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Handle filter changes and search
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const statusFilter = document.getElementById('statusFilter');
    const methodFilter = document.getElementById('methodFilter');
    const searchInput = document.getElementById('searchInput');
    
    // Auto-submit when status filter changes
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            console.log('Status filter changed to:', this.value);
            filterForm.submit();
        });
    }
    
    // Auto-submit when payment method filter changes
    if (methodFilter) {
        methodFilter.addEventListener('change', function() {
            console.log('Payment method filter changed to:', this.value);
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
    console.log('Current filters:', {
        status: statusFilter ? statusFilter.value : null,
        method: methodFilter ? methodFilter.value : null,
        search: searchInput ? searchInput.value : null
    });
});

function showPaymentDetail(paymentId) {
    const modal = document.getElementById('paymentDetailModal');
    const content = document.getElementById('paymentDetailContent');
    
    // Show modal with loading
    modal.classList.remove('hidden');
    content.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
    `;
    
    // Fetch payment detail
    fetch(`/admin/payments/${paymentId}/ajax`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const payment = data.payment;
                content.innerHTML = `
                    <div class="space-y-4">
                        <!-- Info Pembayaran -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-credit-card mr-1.5 text-sm"></i>Informasi Pembayaran
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">ID Pembayaran</span>
                                    <span class="font-medium text-gray-900">#${payment.id}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Jumlah</span>
                                    <span class="font-semibold text-green-600">Rp ${payment.formatted_amount}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Metode Pembayaran</span>
                                    <span class="font-medium text-gray-900">${payment.formatted_payment_method}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Tanggal Pembayaran</span>
                                    <span class="font-medium text-gray-900">${payment.formatted_date}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Status</span>
                                    <span>${payment.status_badge}</span>
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
                                    <span class="font-medium text-gray-900">${payment.booking.renter.name}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Email</span>
                                    <span class="font-medium text-gray-900 text-right truncate ml-2">${payment.booking.renter.email}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Telepon</span>
                                    <span class="font-medium text-gray-900">${payment.booking.renter.phone || '-'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Booking -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-calendar-check mr-1.5 text-sm"></i>Informasi Booking
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">ID Booking</span>
                                    <span class="font-medium text-blue-600">#${payment.booking.id}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Motor</span>
                                    <span class="font-medium text-gray-900">${payment.booking.motor.brand} ${payment.booking.motor.model}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Tipe</span>
                                    <span class="font-medium text-gray-900">${payment.booking.motor.type_cc}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Plat Nomor</span>
                                    <span class="font-medium text-gray-900">${payment.booking.motor.plate_number}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Periode Sewa</span>
                                    <span class="font-medium text-gray-900 text-right ml-2">${payment.booking.formatted_rental_period}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Durasi</span>
                                    <span class="font-medium text-gray-900">${payment.booking.duration} hari</span>
                                </div>
                            </div>
                        </div>

                        ${payment.proof_image_url ? `
                        <!-- Bukti Transfer -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-image mr-1.5 text-sm"></i>Bukti Transfer
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <a href="${payment.proof_image_url}" target="_blank" class="block">
                                    <img src="${payment.proof_image_url}" alt="Bukti Transfer" class="w-full rounded-lg border border-gray-200 hover:opacity-90 transition">
                                </a>
                                <a href="${payment.proof_image_url}" target="_blank" class="mt-2 text-xs text-blue-600 hover:text-blue-700 flex items-center">
                                    <i class="bi bi-box-arrow-up-right mr-1"></i>Buka di tab baru
                                </a>
                            </div>
                        </div>
                        ` : ''}

                        ${payment.verified_at ? `
                        <!-- Info Verifikasi -->
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-check-circle mr-1.5 text-sm"></i>Informasi Verifikasi
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Diverifikasi oleh</span>
                                    <span class="font-medium text-gray-900">${payment.verified_by.name}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600">Tanggal Verifikasi</span>
                                    <span class="font-medium text-gray-900">${payment.formatted_verified_at}</span>
                                </div>
                                ${payment.payment_notes ? `
                                <div class="text-xs">
                                    <span class="text-gray-600">Catatan</span>
                                    <p class="font-medium text-gray-900 mt-1">${payment.payment_notes}</p>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        ` : ''}

                        <!-- Action Buttons -->
                        ${!payment.verified_at ? `
                        <div class="flex gap-2 pt-3 border-t border-gray-200">
                            <button onclick="closePaymentDetailModal(); verifyPayment(${payment.id})" class="flex-1 px-3 py-2 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center gap-1.5">
                                <i class="bi bi-check-circle"></i>
                                Verifikasi
                            </button>
                            <button onclick="closePaymentDetailModal(); rejectPayment(${payment.id})" class="flex-1 px-3 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center gap-1.5">
                                <i class="bi bi-x-circle"></i>
                                Tolak
                            </button>
                        </div>
                        ` : ''}
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="bi bi-exclamation-triangle text-4xl text-red-500 mb-2 block"></i>
                        <p class="text-gray-700">Gagal memuat detail pembayaran</p>
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

function closePaymentDetailModal() {
    document.getElementById('paymentDetailModal').classList.add('hidden');
}

function verifyPayment(paymentId) {
    if (!confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/payments/${paymentId}/verify`;
    
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
    statusInput.value = 'paid';
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    form.appendChild(statusInput);
    document.body.appendChild(form);
    form.submit();
}

function rejectPayment(paymentId) {
    const reason = prompt('Alasan penolakan pembayaran:');
    if (!reason) {
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/payments/${paymentId}/verify`;
    
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
    statusInput.value = 'failed';
    
    const reasonInput = document.createElement('input');
    reasonInput.type = 'hidden';
    reasonInput.name = 'rejection_reason';
    reasonInput.value = reason;
    
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    form.appendChild(statusInput);
    form.appendChild(reasonInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
