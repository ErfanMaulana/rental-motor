@extends('layouts.fann')

@section('title', 'Riwayat Pembayaran')

@section('content')
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Riwayat Pembayaran</h1>
    <p class="text-gray-600 mt-1">Lihat semua riwayat pembayaran dan transaksi Anda</p>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-content between items-center">
        <h5 class="text-lg font-semibold text-gray-900">
            <i class="bi bi-clock-history mr-2"></i>Daftar Riwayat Pembayaran
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
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                onclick="alert('Detail pembayaran akan ditampilkan')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if($payment->status === 'paid')
                                            <a href="{{ route('penyewa.payment.invoice', $payment->id) }}" 
                                               class="px-3 py-1 text-sm text-green-600 hover:bg-green-50 rounded-lg transition"
                                               target="_blank">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @endif
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

@endsection