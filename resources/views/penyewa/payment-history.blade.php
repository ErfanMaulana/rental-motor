@extends('layouts.fann')

@section('title', 'Riwayat Pembayaran - Lihat semua riwayat pembayaran dan transaksi Anda')

@section('content')
<!-- Content Header -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h5 class="text-lg font-semibold text-gray-900">
            <i class="mr-2"></i>Daftar Riwayat Pembayaran
        </h5>
        <a href="{{ route('penyewa.bookings') }}" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
            <i class="bi bi-calendar-check mr-1"></i>Lihat Booking
        </a>
    </div>
    <div class="p-6">
        @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Booking #{{ $payment->booking->id }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $payment->booking->start_date->format('d M Y') }} - 
                                        {{ $payment->booking->end_date->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($payment->booking->motor->photo)
                                            <img src="{{ Storage::url($payment->booking->motor->photo) }}" 
                                                 alt="{{ $payment->booking->motor->brand }}"
                                                 class="w-10 h-10 rounded object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center mr-3">
                                                <i class="bi bi-scooter text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $payment->booking->motor->brand }} {{ $payment->booking->motor->model }}</div>
                                            <div class="text-xs text-gray-500">{{ $payment->booking->motor->type_cc }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
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
                                                <i class="bi bi-bank mr-1"></i>Bank Transfer
                                            </span>
                                        @elseif($payment->payment_method === 'shopeepay')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded">
                                                <i class="bi bi-wallet2 mr-1"></i>ShopeePay
                                            </span>
                                        @endif
                                    @else
                                        @php
                                            $methodLabels = [
                                                'cash' => 'Tunai',
                                                'bank_transfer' => 'Transfer Bank',
                                                'e_wallet' => 'E-Wallet',
                                                'credit_card' => 'Kartu Kredit'
                                            ];
                                            $methodColors = [
                                                'cash' => 'bg-green-100 text-green-800',
                                                'bank_transfer' => 'bg-blue-100 text-blue-800',
                                                'e_wallet' => 'bg-yellow-100 text-yellow-800',
                                                'credit_card' => 'bg-purple-100 text-purple-800'
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $methodColors[$payment->method] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $methodLabels[$payment->method] ?? ucfirst($payment->method) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @switch($payment->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            @break
                                        @case('paid')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                            @break
                                        @case('failed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>
                                            @break
                                        @case('refunded')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Dikembalikan</span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($payment->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <button type="button" 
                                                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition"
                                                onclick="toggleDropdown({{ $payment->id }})">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-{{ $payment->id }}" 
                                             class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                            <div class="py-1">
                                                <button type="button"
                                                        onclick="showPaymentDetail({{ $payment->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                    <i class="bi bi-eye mr-2"></i>
                                                    Detail Pesanan
                                                </button>
                                                @if($payment->status === 'paid')
                                                    <a href="{{ route('penyewa.payment.invoice', $payment->id) }}" 
                                                       target="_blank"
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                        <i class="bi bi-file-earmark-pdf mr-2"></i>
                                                        Export PDF
                                                    </a>
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
            
            <!-- Pagination -->
            @if($payments->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $payments->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="bi bi-credit-card text-gray-300 text-6xl"></i>
                <h4 class="mt-4 text-xl font-semibold text-gray-600">Belum Ada Riwayat Pembayaran</h4>
                <p class="text-gray-500 mt-2">Anda belum memiliki riwayat pembayaran.</p>
                <a href="{{ route('penyewa.motors') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="bi bi-scooter mr-1"></i>Sewa Motor Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal Detail Pesanan -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-4 border w-11/12 md:w-2/3 lg:w-2/5 max-w-lg shadow-xl rounded-xl bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Detail Pesanan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div id="modalContent" class="mt-3">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
    dropdowns.forEach(dropdown => {
        if (!event.target.closest('.relative')) {
            dropdown.classList.add('hidden');
        }
    });
});

function toggleDropdown(id) {
    event.stopPropagation();
    const dropdown = document.getElementById('dropdown-' + id);
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(dd => {
        if (dd.id !== 'dropdown-' + id) {
            dd.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function showPaymentDetail(paymentId) {
    const payments = @json($payments->items());
    const payment = payments.find(p => p.id === paymentId);
    
    if (!payment) return;
    
    const content = `
        <div class="space-y-4">
            <!-- Informasi Booking -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="bi bi-calendar-check mr-2 text-blue-600"></i>
                    Informasi Booking
                </h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-500">Booking ID</p>
                        <p class="font-semibold">#${payment.booking.id}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Booking</p>
                        <p class="font-semibold">${new Date(payment.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Sewa</p>
                        <p class="font-semibold">${new Date(payment.booking.start_date).toLocaleDateString('id-ID')}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Kembali</p>
                        <p class="font-semibold">${new Date(payment.booking.end_date).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Motor -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="bi bi-scooter mr-2 text-blue-600"></i>
                    Informasi Motor
                </h4>
                <div class="flex items-center gap-3 mb-3">
                    ${payment.booking.motor.photo 
                        ? `<img src="/storage/${payment.booking.motor.photo}" class="w-20 h-20 rounded object-cover" alt="Motor">`
                        : `<div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                             <i class="bi bi-scooter text-gray-400 text-2xl"></i>
                           </div>`
                    }
                    <div>
                        <p class="font-bold text-lg">${payment.booking.motor.brand} ${payment.booking.motor.model}</p>
                        <p class="text-sm text-gray-600">${payment.booking.motor.type_cc}</p>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Pembayaran -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="bi bi-credit-card mr-2 text-blue-600"></i>
                    Informasi Pembayaran
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-lg">Rp ${formatCurrency(payment.amount)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-semibold">
                            ${payment.payment_method === 'dana' ? '<span class="text-blue-600">DANA</span>' : ''}
                            ${payment.payment_method === 'gopay' ? '<span class="text-green-600">GoPay</span>' : ''}
                            ${payment.payment_method === 'shopeepay' ? '<span class="text-orange-600">ShopeePay</span>' : ''}
                            ${payment.payment_method === 'bank' ? '<span class="text-purple-600">Transfer Bank</span>' : ''}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                            payment.status === 'paid' ? 'bg-green-100 text-green-800' : 
                            payment.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-red-100 text-red-800'
                        }">
                            ${payment.status === 'paid' ? 'Lunas' : payment.status === 'pending' ? 'Menunggu' : 'Gagal'}
                        </span>
                    </div>
                </div>
            </div>
            
            ${payment.status === 'paid' ? `
            <div class="pt-4 border-t">
                <a href="/penyewa/payments/${payment.id}/download-pdf" 
                   class="w-full block text-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="bi bi-file-earmark-pdf mr-2"></i>
                    Download Invoice PDF
                </a>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('detailModal').classList.remove('hidden');
    
    // Close dropdown
    document.getElementById('dropdown-' + paymentId).classList.add('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush

@endsection