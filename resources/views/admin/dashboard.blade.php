@extends('layouts.fann')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Primary Statistics -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-blue-100">
                            <i class="bi bi-people text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-cyan-100">
                            <i class="bi bi-scooter text-2xl text-cyan-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Motor</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalMotors }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-yellow-100">
                            <i class="bi bi-calendar-check text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pemesanan</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-lg bg-green-100">
                            <i class="bi bi-currency-dollar text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pendapatan</dt>
                            <dd class="text-lg font-bold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-700">{{ $totalPenyewa }}</div>
            <div class="text-xs text-blue-600 mt-1">Penyewa</div>
        </div>
        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-cyan-700">{{ $totalPemilik }}</div>
            <div class="text-xs text-cyan-600 mt-1">Pemilik</div>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-700">{{ $pendingMotorsCount }}</div>
            <div class="text-xs text-yellow-600 mt-1">Perlu Verifikasi</div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-700">{{ $availableMotors }}</div>
            <div class="text-xs text-green-600 mt-1">Motor Tersedia</div>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-700">{{ $pendingBookings }}</div>
            <div class="text-xs text-purple-600 mt-1">Booking Pending</div>
        </div>
        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-pink-700">{{ $activeBookings }}</div>
            <div class="text-xs text-pink-600 mt-1">Booking Aktif</div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="bi bi-graph-up mr-2 text-blue-600"></i>
                Trend Pendapatan (12 Bulan Terakhir)
            </h2>
            <a href="{{ route('admin.financial-report') }}" 
               class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                Lihat Detail
                <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="p-6">
            @if(count($chartData['labels'] ?? []) > 0)
                <canvas id="revenueChart" height="80"></canvas>
            @else
                <div class="text-center py-12">
                    <i class="bi bi-graph-up text-gray-300 text-6xl"></i>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada data pendapatan</h3>
                </div>
            @endif
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-calendar-check mr-2 text-blue-600"></i>
                        Pemesanan Terbaru
                    </h2>
                    <a href="{{ route('admin.bookings') }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center">
                        Lihat Semua
                        <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    @if($recentBookings->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'completed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-12">
                        <i class="bi bi-calendar-x text-gray-300 text-6xl"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada pemesanan</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Action Required -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-exclamation-circle mr-2 text-orange-600"></i>
                        Perlu Tindakan
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($pendingMotorsCount > 0)
                    <a href="{{ route('admin.motors') }}?status=pending_verification" 
                       class="block p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded hover:bg-yellow-100 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">Motor Perlu Verifikasi</p>
                                <p class="text-xs text-yellow-600 mt-1">{{ $pendingMotorsCount }} motor menunggu</p>
                            </div>
                            <i class="bi bi-arrow-right text-yellow-600 text-lg"></i>
                        </div>
                    </a>
                    @endif
                    
                    @if($pendingBookings > 0)
                    <a href="{{ route('admin.bookings') }}?status=pending" 
                       class="block p-4 bg-blue-50 border-l-4 border-blue-400 rounded hover:bg-blue-100 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-blue-800">Booking Menunggu</p>
                                <p class="text-xs text-blue-600 mt-1">{{ $pendingBookings }} booking baru</p>
                            </div>
                            <i class="bi bi-arrow-right text-blue-600 text-lg"></i>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('admin.payments') }}" 
                       class="block p-4 bg-purple-50 border-l-4 border-purple-400 rounded hover:bg-purple-100 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-purple-800">Verifikasi Pembayaran</p>
                                <p class="text-xs text-purple-600 mt-1">Cek pembayaran pending</p>
                            </div>
                            <i class="bi bi-arrow-right text-purple-600 text-lg"></i>
                        </div>
                    </a>

                    @if($pendingMotorsCount == 0 && $pendingBookings == 0)
                    <div class="text-center py-4 bg-green-50 rounded-lg border border-green-200">
                        <i class="bi bi-check-circle text-green-500 text-3xl"></i>
                        <p class="text-sm text-green-700 font-medium mt-2">Semua Booking & Motor Terverifikasi!</p>
                        <p class="text-xs text-green-600 mt-1">Sistem berjalan lancar</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(count($chartData['labels'] ?? []) > 0)
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Total Pendapatan',
                        data: {!! json_encode($chartData['total_revenue']) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Komisi Admin (30%)',
                        data: {!! json_encode($chartData['admin_commission']) !!},
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Bagian Pemilik (70%)',
                        data: {!! json_encode($chartData['owner_share']) !!},
                        borderColor: 'rgb(251, 146, 60)',
                        backgroundColor: 'rgba(251, 146, 60, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    @endif
</script>
@endpush
