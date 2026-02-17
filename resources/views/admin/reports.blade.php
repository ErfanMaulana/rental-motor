@extends('layouts.fann')

@section('title', 'Laporan')

@section('content')

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Pendapatan</p>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalRevenue / 1000000, 1) }}M</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Komisi Admin ({{ $commissionRate }}%)</p>
            <p class="text-2xl font-semibold text-green-600">{{ number_format($adminCommission / 1000000, 1) }}M</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Pendapatan Pemilik</p>
            <p class="text-2xl font-semibold text-blue-600">{{ number_format($ownerRevenue / 1000000, 1) }}M</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Transaksi</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $totalTransactions }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
        <h5 class="text-lg font-semibold text-gray-900 mb-4">Grafik Pendapatan Bulanan</h5>
        @if(count($chartData['labels'] ?? []) > 0)
            <canvas id="revenueChart" style="height: 300px;"></canvas>
        @else
            <div class="py-12 text-center text-gray-400">
                <i class="bi bi-graph-up text-4xl mb-2 block"></i>
                <p class="text-sm">Belum ada data untuk ditampilkan</p>
            </div>
        @endif
    </div>
    
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <h5 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Pendapatan</h5>
        <canvas id="distributionChart" style="height: 200px;"></canvas>
        <div class="mt-4 space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Komisi Admin</span>
                <span class="text-sm font-semibold text-gray-900">{{ $commissionRate }}%</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Pendapatan Pemilik</span>
                <span class="text-sm font-semibold text-gray-900">{{ 100 - $commissionRate }}%</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-6">
    <div class="px-4 py-3 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Revenue Sharing</h5>
    </div>
    @if(count($revenueSharing ?? []) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Booking</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Motor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Pemilik</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Admin (30%)</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Pemilik (70%)</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($revenueSharing as $rs)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $rs->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $rs->booking_id }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $rs->booking->motor->brand ?? 'N/A' }} {{ $rs->booking->motor->model ?? '' }}</div>
                        <div class="text-xs text-gray-500">{{ $rs->booking->motor->plate_number ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $rs->owner->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $rs->owner->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($rs->total_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-green-600">Rp {{ number_format($rs->admin_commission ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-blue-600">Rp {{ number_format($rs->owner_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if($rs->status == 'paid') bg-green-100 text-green-700
                            @elseif($rs->status == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($rs->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($revenueSharing->hasPages())
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $revenueSharing->links() }}
    </div>
    @endif
    @else
    <div class="px-4 py-12 text-center">
        <i class="bi bi-inbox text-4xl text-gray-400 mb-2 block"></i>
        <h5 class="text-gray-900 font-medium mb-1">Tidak ada data revenue sharing</h5>
        <p class="text-sm text-gray-500">Belum ada transaksi yang menghasilkan revenue sharing</p>
    </div>
    @endif
</div>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Transaksi Selesai</h5>
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Durasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Total Harga</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $transaction->renter->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $transaction->renter->email ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium text-gray-900">{{ $transaction->motor->brand ?? 'N/A' }} {{ $transaction->motor->model ?? '' }}</div>
                        <div class="text-xs text-gray-500">{{ $transaction->motor->plate_number ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $transaction->duration ?? 0 }} hari</span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->price ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $transactions->links() }}
    </div>
    @endif
    @else
    <div class="px-4 py-12 text-center">
        <i class="bi bi-inbox text-4xl text-gray-400 mb-2 block"></i>
        <h5 class="text-gray-900 font-medium mb-1">Tidak ada transaksi</h5>
        <p class="text-sm text-gray-500">Belum ada transaksi yang selesai</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($chartData['labels'] ?? []) > 0)
    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart');
    if (ctxRevenue) {
        new Chart(ctxRevenue.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [{
                    label: 'Total Pendapatan',
                    data: @json($chartData['revenue'] ?? []),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Komisi Admin',
                    data: @json($chartData['admin_commission'] ?? []),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                    legend: { display: true, position: 'top' },
                    tooltip: {
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

    // Distribution Chart
    const ctxDist = document.getElementById('distributionChart');
    if (ctxDist) {
        new Chart(ctxDist.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Komisi Admin ({{ $commissionRate }}%)', 'Pendapatan Pemilik ({{ 100 - $commissionRate }}%)'],
                datasets: [{
                    data: [{{ $commissionRate }}, {{ 100 - $commissionRate }}],
                    backgroundColor: ['rgb(34, 197, 94)', 'rgb(59, 130, 246)'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
