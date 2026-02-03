@extends('layouts.fann')

@section('title', 'Laporan Pendapatan')

@section('content')
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Laporan Pendapatan</h1>
    <p class="text-gray-600 mt-1">Analisis pendapatan dari penyewaan motor Anda</p>
</div>

<div class="space-y-6">
    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h5 class="text-lg font-semibold text-gray-900">
                <i class="bi bi-graph-up mr-2"></i>Laporan Pendapatan Motor
            </h5>
            <div>
                <form method="GET" action="{{ route('pemilik.revenue.export.pdf') }}" style="display: inline;">
                    <input type="hidden" name="month" value="{{ request('month') }}">
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition inline-flex items-center">
                        <i class="bi bi-file-pdf mr-2"></i> Export Laporan
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Filter Periode -->
            <form method="GET" action="{{ route('pemilik.revenue.report') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="month" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="year" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Tahun</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </form>

            <!-- Ringkasan Pendapatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h4>
                            <p class="text-gray-500 text-sm mt-1">Total Pendapatan Anda</p>
                        </div>
                        <i class="bi bi-cash-stack text-5xl text-gray-300"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-2xl font-bold text-green-600">{{ $revenues->total() }}</h4>
                            <p class="text-gray-500 text-sm mt-1">Total Transaksi</p>
                        </div>
                        <i class="bi bi-list-check text-5xl text-gray-300"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-2xl font-bold text-blue-600">70%</h4>
                            <p class="text-gray-500 text-sm mt-1">Bagi Hasil untuk Anda</p>
                        </div>
                        <i class="bi bi-percent text-5xl text-gray-300"></i>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Pendapatan -->
            <div class="overflow-x-auto">
                @if($revenues->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyewa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan Anda</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komisi Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($revenues as $revenue)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $revenue->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-blue-600">#{{ $revenue->booking_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $revenue->booking->motor->brand }} {{ $revenue->booking->motor->model }}</div>
                                        <div class="text-xs text-gray-500">{{ $revenue->booking->motor->plate_number }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $revenue->booking->renter->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $revenue->booking->renter->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900">Rp {{ number_format($revenue->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-green-600">Rp {{ number_format($revenue->owner_amount, 0, ',', '.') }}</span>
                                    <br><span class="text-xs text-gray-500">70% dari total</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-600">Rp {{ number_format($revenue->admin_commission, 0, ',', '.') }}</span>
                                    <br><span class="text-xs text-gray-500">30% komisi</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                            <tr>
                                <th colspan="5" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Total:</th>
                                <th class="px-6 py-3 text-left font-bold text-green-600">Rp {{ number_format($revenues->sum('owner_amount'), 0, ',', '.') }}</th>
                                <th class="px-6 py-3 text-left font-bold text-gray-600">Rp {{ number_format($revenues->sum('admin_commission'), 0, ',', '.') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-graph-down text-gray-300 text-6xl"></i>
                        <h4 class="mt-4 text-xl font-semibold text-gray-600">Belum ada data pendapatan</h4>
                        <p class="text-gray-500 mt-2">Pendapatan akan muncul setelah booking motor Anda selesai</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($revenues->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $revenues->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h6 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-info-circle mr-2 text-blue-600"></i>Informasi Bagi Hasil
            </h6>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="bi bi-check-circle text-green-600 mr-2 text-xl"></i>
                            <span class="text-gray-700">Anda mendapat <strong class="text-gray-900">70%</strong> dari setiap booking yang selesai</span>
                        </li>
                        <li class="flex items-start">
                            <i class="bi bi-check-circle text-green-600 mr-2 text-xl"></i>
                            <span class="text-gray-700">Admin mendapat <strong class="text-gray-900">30%</strong> sebagai komisi platform</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="bi bi-check-circle text-green-600 mr-2 text-xl"></i>
                            <span class="text-gray-700">Pendapatan dihitung otomatis saat booking selesai</span>
                        </li>
                        <li class="flex items-start">
                            <i class="bi bi-check-circle text-green-600 mr-2 text-xl"></i>
                            <span class="text-gray-700">Laporan dapat didownload dalam format PDF</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection