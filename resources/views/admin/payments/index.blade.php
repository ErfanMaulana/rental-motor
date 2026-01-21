@extends('layouts.fann')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
        <i class="bi bi-credit-card text-blue-600 mr-3"></i>
        Verifikasi Pembayaran
    </h1>
    <p class="text-sm text-gray-500 mt-1 ml-11">Kelola dan verifikasi pembayaran dari penyewa</p>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pembayaran</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $summary['total_payments'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Menunggu Verifikasi</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $summary['unverified_payments'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Sudah Diverifikasi</p>
            <p class="text-2xl font-semibold text-green-600">{{ $summary['verified_payments'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Nilai Pending</p>
            <p class="text-2xl font-semibold text-blue-600">{{ number_format($summary['pending_amount'] / 1000000, 1) }}M</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.payments') }}" class="flex flex-wrap gap-3">
        <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Belum Diverifikasi</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Sudah Diverifikasi</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
        </select>
        <select name="payment_method" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Metode</option>
            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
            <option value="e_wallet" {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nama penyewa, email, atau ID booking...">
        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('admin.payments') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" title="Reset">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </form>
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
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $payment->formatted_payment_method }}</span>
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
                            <div class="text-xs text-gray-500 mt-1">oleh {{ $payment->verifiedBy->name }}</div>
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
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <button onclick="alert('Detail payment #{{ $payment->id }}')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if(!$payment->verified_at)
                            <button onclick="if(confirm('Verifikasi pembayaran ini?')) window.location.href='#'" class="p-1.5 text-green-600 hover:bg-green-50 rounded" title="Verifikasi">
                                <i class="bi bi-check"></i>
                            </button>
                            <button onclick="if(confirm('Tolak pembayaran ini?')) window.location.href='#'" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Tolak">
                                <i class="bi bi-x"></i>
                            </button>
                            @endif
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

@endsection
