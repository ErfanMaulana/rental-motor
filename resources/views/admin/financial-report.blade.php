@extends('layouts.fann')

@section('title', 'Laporan Keuangan')

@section('content')

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pendapatan</p>
            <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Komisi Admin (30%)</p>
            <p class="text-2xl font-semibold text-green-600">Rp {{ number_format($summary['admin_commission'], 0, ',', '.') }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Bagian Pemilik (70%)</p>
            <p class="text-2xl font-semibold text-yellow-600">Rp {{ number_format($summary['owner_amount'], 0, ',', '.') }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Transaksi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $summary['total_bookings'] }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.financial-report') }}" class="flex flex-wrap gap-3 items-center">
        <select name="month" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Bulan</option>
            <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>Januari</option>
            <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>Februari</option>
            <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>Maret</option>
            <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
            <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>Mei</option>
            <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>Juni</option>
            <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>Juli</option>
            <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>Agustus</option>
            <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
            <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Oktober</option>
            <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
            <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Desember</option>
        </select>
        <select name="year" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Tahun</option>
            @for($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <a href="{{ route('admin.financial-report') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            <i class="bi bi-arrow-clockwise"></i> Reset Filter
        </a>
        <a href="{{ route('admin.financial-report.export-pdf', request()->query()) }}" target="_blank" class="ml-auto px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="bi bi-download"></i> Export
        </a>
        <button type="button" onclick="window.print()" class="px-4 py-2 text-sm bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="bi bi-printer"></i> Print
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
        <h5 class="text-lg font-semibold text-gray-900 mb-4">Trend Pendapatan</h5>
        @if(count($chartData['labels'] ?? []) > 0)
            <div style="height: 300px; width: 100%; position: relative;">
                <canvas id="revenueChart" width="800" height="300"></canvas>
            </div>
        @else
            <div class="py-12 text-center text-gray-400">
                <i class="bi bi-graph-up text-4xl mb-2 block"></i>
                <p class="text-sm">Belum ada data untuk ditampilkan</p>
            </div>
        @endif
    </div>
    
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <h5 class="text-lg font-semibold text-gray-900 mb-4">Top Motor Terlaris</h5>
        @forelse($topMotors ?? [] as $motor)
            @if($motor && isset($motor['motor']))
            <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-100 last:border-0">
                <div>
                    <div class="font-medium text-gray-900">{{ $motor['motor']->brand ?? 'N/A' }} {{ $motor['motor']->model ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500">{{ $motor['motor']->plate_number ?? 'N/A' }}</div>
                </div>
                <div class="text-right">
                    <div class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded mb-1">{{ $motor['booking_count'] ?? 0 }} sewa</div>
                    <div class="text-xs text-gray-600">Rp {{ number_format($motor['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            @endif
        @empty
        <div class="py-8 text-center text-gray-400">
            <i class="bi bi-trophy text-3xl mb-2 block"></i>
            <p class="text-sm">Belum ada data</p>
        </div>
        @endforelse
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
    <div class="px-4 py-3 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Detail Transaksi</h5>
    </div>
    @if(count($transactions ?? []) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Kode Booking</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Penyewa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Motor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Pemilik</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Komisi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Bagian Pemilik</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ optional($transaction->created_at)->format('d/m/Y') ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#BK{{ str_pad($transaction->booking_id ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ optional(optional($transaction->booking)->renter)->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ optional(optional($transaction->booking)->renter)->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ optional(optional($transaction->booking)->motor)->brand ?? 'N/A' }} {{ optional(optional($transaction->booking)->motor)->model ?? '' }}</div>
                        <div class="text-xs text-gray-500">{{ optional(optional($transaction->booking)->motor)->license_plate ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ optional($transaction->owner)->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ optional($transaction->owner)->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-green-600">Rp {{ number_format($transaction->admin_commission ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-blue-600">Rp {{ number_format($transaction->owner_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if(($transaction->status ?? '') == 'paid') bg-green-100 text-green-700
                            @elseif(($transaction->status ?? '') == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($transaction->status ?? 'unknown') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if(isset($transactions) && $transactions->hasPages())
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $transactions->links() }}
    </div>
    @endif
    @else
    <div class="px-4 py-12 text-center">
        <i class="bi bi-inbox text-4xl text-gray-400 mb-2 block"></i>
        <h5 class="text-gray-900 font-medium mb-1">Tidak ada transaksi</h5>
        <p class="text-sm text-gray-500">Tidak ada transaksi dalam periode ini</p>
    </div>
    @endif
</div>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Ringkasan per Pemilik Motor</h5>
    </div>
    @if(count($ownerSummary ?? []) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Pemilik</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Jumlah Motor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total Transaksi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total Pendapatan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Bagian Pemilik (70%)</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Komisi Admin (30%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($ownerSummary as $ownerData)
                @if($ownerData && is_object($ownerData))
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $ownerData->owner ? $ownerData->owner->name : 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $ownerData->owner ? $ownerData->owner->email : 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $ownerData->motor_count ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">{{ $ownerData->transaction_count ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($ownerData->total_revenue ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-blue-600">Rp {{ number_format($ownerData->owner_earned ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-green-600">Rp {{ number_format($ownerData->admin_earned ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="px-4 py-12 text-center">
        <i class="bi bi-people text-4xl text-gray-400 mb-2 block"></i>
        <h5 class="text-gray-900 font-medium mb-1">Tidak ada data pemilik</h5>
        <p class="text-sm text-gray-500">Belum ada data revenue sharing dari pemilik</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($chartData['labels'] ?? []) > 0)
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [
                    {
                        label: 'Total Pendapatan',
                        data: @json($chartData['revenue'] ?? []),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0
                    },
                    {
                        label: 'Komisi Admin (30%)',
                        data: @json($chartData['admin_commission'] ?? []),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0
                    },
                    {
                        label: 'Bagian Pemilik (70%)',
                        data: @json($chartData['owner_share'] ?? []),
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        borderWidth: 2,
                        borderDash: [10, 5],
                        fill: false,
                        tension: 0
                    }
                ]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                animation: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        animation: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
