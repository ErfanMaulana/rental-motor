@extends('layouts.fann')

@section('title', 'Detail Motor - ' . $motor->brand . ' ' . $motor->model)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Motor - {{ $motor->brand }} {{ $motor->model }}</h1>
            <p class="text-gray-600 mt-1">{{ $motor->plate_number }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pemilik.motors') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-flex items-center">
                <i class="bi bi-arrow-left mr-2"></i>Kembali
            </a>
            @if(Auth::user()->isVerified())
                <a href="{{ route('pemilik.motor.edit', $motor->id) }}" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                    <i class="bi bi-pencil mr-2"></i>Edit Motor
                </a>
                <button type="button" onclick="confirmDelete({{ $motor->id }})" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition inline-flex items-center">
                    <i class="bi bi-trash mr-2"></i>Hapus Motor
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Motor Detail Card -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Motor Image -->
            @if($motor->photo)
                <img src="{{ Storage::url($motor->photo) }}" 
                     class="w-full object-cover" 
                     alt="{{ $motor->brand }}"
                     style="aspect-ratio: 4/3; object-fit: cover;">
            @else
                <div class="bg-gray-100 flex items-center justify-center" style="aspect-ratio: 4/3;">
                    <div class="text-center text-gray-400">
                        <i class="bi bi-camera text-6xl"></i>
                        <p class="mt-2">Tidak ada foto</p>
                    </div>
                </div>
            @endif
            
            <!-- Motor Info -->
            <div class="p-4">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $motor->brand }} {{ $motor->model }}</h2>
                        <p class="text-gray-600 mt-1">{{ $motor->plate_number }}</p>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'available' => 'bg-green-500',
                                'rented' => 'bg-orange-500',
                                'maintenance' => 'bg-gray-500',
                                'pending_verification' => 'bg-yellow-500'
                            ];
                            $statusTexts = [
                                'available' => 'Tersedia',
                                'rented' => 'Disewa',
                                'maintenance' => 'Maintenance',
                                'pending_verification' => 'Menunggu Verifikasi'
                            ];
                        @endphp
                        <span class="px-3 py-1 {{ $statusColors[$motor->status] ?? 'bg-gray-500' }} text-white text-sm font-semibold rounded-full">
                            {{ $statusTexts[$motor->status] ?? ucfirst($motor->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="bi bi-gear text-blue-600 text-xl mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-500">Tipe CC</p>
                            <p class="font-semibold text-gray-900">{{ $motor->type_cc }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="bi bi-calendar text-blue-600 text-xl mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-500">Tahun</p>
                            <p class="font-semibold text-gray-900">{{ $motor->year }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="bi bi-palette text-blue-600 text-xl mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-500">Warna</p>
                            <p class="font-semibold text-gray-900">{{ $motor->color }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="bi bi-person text-blue-600 text-xl mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-500">Pemilik</p>
                            <p class="font-semibold text-gray-900">{{ $motor->owner->name }}</p>
                        </div>
                    </div>
                </div>
                
                @if($motor->description)
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Deskripsi</h3>
                    <p class="text-gray-700">{{ $motor->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Rental Rate Card -->
        @if($motor->rentalRate)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mt-4">
            <div class="bg-blue-600 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">
                    <i class="bi bi-cash-stack mr-2"></i>Harga Sewa
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Harian</p>
                        <p class="text-lg font-bold text-blue-600">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Mingguan</p>
                        <p class="text-lg font-bold text-blue-600">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Bulanan</p>
                        <p class="text-lg font-bold text-blue-600">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-4">
        <!-- Statistics Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">
                    <i class="bi bi-graph-up mr-2"></i>Statistik Motor
                </h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-center p-2 bg-blue-50 rounded-lg">
                    <span class="text-sm text-gray-700">Total Booking</span>
                    <span class="text-xl font-bold text-blue-600">{{ $motor->bookings->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-yellow-50 rounded-lg">
                    <span class="text-sm text-gray-700">Booking Aktif</span>
                    <span class="text-xl font-bold text-yellow-600">{{ $motor->bookings->whereIn('status', ['confirmed', 'active'])->count() }}</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-green-50 rounded-lg">
                    <span class="text-sm text-gray-700">Booking Selesai</span>
                    <span class="text-xl font-bold text-green-600">{{ $motor->bookings->where('status', 'completed')->count() }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-calendar-plus mr-2"></i>
                        <span>Terdaftar: <strong>{{ $motor->created_at->format('d/m/Y') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">
                    <i class="bi bi-calendar-check mr-2"></i>Booking Terbaru
                </h3>
            </div>
            <div class="p-4">
                @if($motor->bookings->count() > 0)
                    <div class="space-y-3">
                        @foreach($motor->bookings->take(5) as $booking)
                            <div class="flex justify-between items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div>
                                    <h6 class="font-semibold text-gray-900">{{ $booking->user->name }}</h6>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-calendar3 mr-1"></i>
                                        {{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div>
                                    @php
                                        $bookingStatusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'active' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $bookingStatusTexts = [
                                            'pending' => 'Menunggu',
                                            'confirmed' => 'Dikonfirmasi',
                                            'active' => 'Aktif',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $bookingStatusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $bookingStatusTexts[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('pemilik.bookings') }}?motor_id={{ $motor->id }}" class="inline-flex items-center px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="bi bi-eye mr-2"></i>Lihat Semua Booking
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="bi bi-calendar-x text-gray-300 text-5xl"></i>
                        <p class="text-gray-500 mt-3">Belum ada booking</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Document Preview -->
        @if($motor->document)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">
                    <i class="bi bi-file-earmark-text mr-2"></i>Dokumen Motor
                </h3>
            </div>
            <div class="p-4">
                <img src="{{ Storage::url($motor->document) }}" 
                     alt="Dokumen Motor" 
                     class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition"
                     onclick="window.open('{{ Storage::url($motor->document) }}', '_blank')">
                <p class="text-xs text-gray-500 text-center mt-2">
                    <i class="bi bi-info-circle mr-1"></i>Klik untuk memperbesar
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-delete-modal.window="open = true"
     @keydown.escape.window="open = false"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
             @click="open = false"></div>

        <!-- Modal panel -->
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-red-600 text-white">
                <h3 class="text-lg font-medium">
                    <i class="bi bi-exclamation-triangle mr-2"></i>Konfirmasi Hapus Motor
                </h3>
                <button @click="open = false" class="text-white hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-4">
                <div class="text-center mb-4">
                    <div class="text-red-600 mb-3">
                        <i class="bi bi-trash text-6xl"></i>
                    </div>
                    <h6 class="font-bold text-lg">Apakah Anda yakin ingin menghapus motor ini?</h6>
                </div>
                
                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <div class="space-y-2">
                        <div class="flex">
                            <div class="w-1/3 text-gray-600 text-sm">Brand/Model:</div>
                            <div class="w-2/3 font-bold">{{ $motor->brand }} {{ $motor->model }}</div>
                        </div>
                        <div class="flex">
                            <div class="w-1/3 text-gray-600 text-sm">Plat Nomor:</div>
                            <div class="w-2/3 font-bold">{{ $motor->plate_number }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <p class="text-sm text-red-700">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Motor dan semua data terkait akan dihapus secara permanen.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50">
                <button @click="open = false" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <form method="POST" action="{{ route('pemilik.motor.delete', $motor->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                        <i class="bi bi-trash mr-2"></i>Hapus Motor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function confirmDelete(motorId) {
    window.dispatchEvent(new CustomEvent('open-delete-modal'));
}
</script>
@endsection