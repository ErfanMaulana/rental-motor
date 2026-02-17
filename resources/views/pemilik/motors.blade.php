@extends('layouts.fann')

@section('title', 'Motor Saya')

@section('content')
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Motor Saya</h1>
    <p class="text-gray-600 mt-1">Kelola semua motor yang telah Anda daftarkan</p>
</div>

<!-- Verification Status Alert -->
@if(!$isVerified)
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="bi bi-shield-exclamation text-yellow-500 text-2xl mr-3"></i>
            <div>
                <h6 class="font-semibold text-yellow-800 mb-1">Perlu Verifikasi Akun</h6>
                <p class="text-yellow-700 text-sm">Anda perlu memverifikasi akun terlebih dahulu sebelum dapat mendaftarkan motor baru. Silakan tunggu admin memverifikasi akun Anda.</p>
            </div>
        </div>
    </div>
@endif

<!-- Error Messages -->
@if($errors->has('verification'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="bi bi-exclamation-triangle text-red-500 text-2xl mr-3"></i>
            <div>
                <h6 class="font-semibold text-red-800 mb-1">Akses Ditolak</h6>
                <p class="text-red-700 text-sm">{{ $errors->first('verification') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Success Message -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="bi bi-check-circle text-green-500 text-2xl mr-3"></i>
            <div>
                <h6 class="font-semibold text-green-800 mb-1">Berhasil!</h6>
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Error Message -->
@if($errors->has('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="bi bi-exclamation-triangle text-red-500 text-2xl mr-3"></i>
            <div>
                <h6 class="font-semibold text-red-800 mb-1">Error!</h6>
                <p class="text-red-700 text-sm">{{ $errors->first('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Action Bar -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        @if($isVerified)
            <a href="{{ route('pemilik.motor.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                <i class="bi bi-plus-circle mr-2"></i>Daftarkan Motor Baru
            </a>
        @else
            <button class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed" disabled>
                <i class="bi bi-shield-exclamation mr-2"></i>Perlu Verifikasi
            </button>
        @endif
    </div>
    <div class="w-full md:w-auto">
        <form method="GET" action="{{ route('pemilik.motors') }}" class="flex gap-2">
            <input type="text" class="flex-1 md:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="search" value="{{ request('search') }}" placeholder="Cari motor...">
            <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="status">
                <option value="">Semua Status</option>
                <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <button class="px-4 py-2 bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-lg transition" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<!-- Motors List -->
@if($motors->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($motors as $motor)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow flex flex-col overflow-visible">
            <!-- Motor Image -->
            <div class="relative h-48 bg-gray-100 overflow-hidden rounded-t-lg">
                @if($motor->photo)
                    <img src="{{ Storage::url($motor->photo) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $motor->brand }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full hidden items-center justify-center bg-gray-100">
                        <div class="text-center">
                            <i class="bi bi-motorcycle text-gray-400 text-5xl"></i>
                            <p class="text-xs text-gray-400 mt-2">Foto tidak ditemukan</p>
                        </div>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                        <div class="text-center">
                            <i class="bi bi-motorcycle text-gray-400 text-5xl"></i>
                            <p class="text-xs text-gray-400 mt-2">Belum ada foto</p>
                        </div>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                    @php
                        $currentStatus = $motor->getCurrentStatus();
                        $currentBooking = $motor->getCurrentBooking();
                    @endphp
                    
                    @if($currentStatus === 'pending_verification')
                        <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full shadow">
                            Menunggu Verifikasi
                        </span>
                    @elseif($currentStatus === 'rented')
                        <span class="px-3 py-1 bg-orange-500 text-white text-xs font-semibold rounded-full shadow">
                            Disewa
                        </span>
                    @elseif($currentStatus === 'available')
                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow">
                            Tersedia
                        </span>
                    @elseif($currentStatus === 'maintenance')
                        <span class="px-3 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full shadow">
                            Maintenance
                        </span>
                    @endif
                </div>
            </div>

            <!-- Motor Info -->
            <div class="p-5 flex-1 flex flex-col">
                <!-- Model Motor (Nama Utama) -->
                <div class="mb-3">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $motor->model }}</h3>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-tag mr-1"></i>
                        <span>{{ $motor->brand }}</span>
                        <span class="mx-2">•</span>
                        <i class="bi bi-credit-card mr-1"></i>
                        <span>{{ $motor->plate_number }}</span>
                        <span class="mx-2">•</span>
                        <i class="bi bi-gear mr-1"></i>
                        <span>{{ $motor->type_cc }}</span>
                    </div>
                </div>
                
                @if($motor->description)
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $motor->description }}</p>
                @endif

                <!-- Rental Rates -->
                @if($motor->rentalRate)
                    <div class="mt-auto pt-3 border-t">
                        <div class="grid grid-cols-3 gap-1 text-center">
                            <div>
                                <small class="text-gray-500 text-[9px] block">Harian</small>
                                <div class="font-semibold text-blue-600 text-[10px]">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <small class="text-gray-500 text-[9px] block">Mingguan</small>
                                <div class="font-semibold text-blue-600 text-[10px]">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <small class="text-gray-500 text-[9px] block">Bulanan</small>
                                <div class="font-semibold text-blue-600 text-[10px]">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer Actions -->
            <div class="px-5 py-3 bg-gray-50 border-t flex items-center justify-between rounded-b-lg relative overflow-visible">
                <span class="text-xs text-gray-500">
                    <i class="bi bi-calendar mr-1"></i>{{ $motor->created_at->diffForHumans() }}
                </span>
                
                <!-- Three Dot Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            @click.away="open = false"
                            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition"
                            title="Menu Aksi">
                        <i class="bi bi-three-dots-vertical text-lg"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         style="display: none;"
                         class="absolute right-0 bottom-16 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-[100] origin-bottom-right">
                        
                        <!-- Detail Option -->
                        <a href="{{ route('pemilik.motor.detail', $motor->id) }}" 
                           class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition rounded-t-lg">
                            <i class="bi bi-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        
                        <!-- Edit Option -->
                        @if($isVerified)
                            <a href="{{ route('pemilik.motor.edit', $motor->id) }}" 
                               class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition">
                                <i class="bi bi-pencil mr-2"></i>
                                Edit Motor
                            </a>
                        @else
                            <div class="px-4 py-2 text-sm text-gray-400 flex items-center opacity-50 cursor-not-allowed">
                                <i class="bi bi-pencil mr-2"></i>
                                Edit Motor (Perlu Verifikasi)
                            </div>
                        @endif
                        
                        <!-- Delete Option -->
                        @if($isVerified)
                            <button type="button" 
                                    onclick="if(confirm('Apakah Anda yakin ingin menghapus motor {{ $motor->brand }} {{ $motor->plate_number }}?\n\nTindakan ini tidak dapat dibatalkan!')) { document.getElementById('delete-form-{{ $motor->id }}').submit(); }"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 flex items-center transition rounded-b-lg">
                                <i class="bi bi-trash mr-2"></i>
                                Hapus Motor
                            </button>
                            <form id="delete-form-{{ $motor->id }}" method="POST" action="{{ route('pemilik.motor.delete', $motor->id) }}" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @else
                            <div class="px-4 py-2 text-sm text-gray-400 flex items-center opacity-50 cursor-not-allowed rounded-b-lg">
                                <i class="bi bi-trash mr-2"></i>
                                Hapus Motor (Perlu Verifikasi)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        {{ $motors->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="text-center py-12 bg-white rounded-lg shadow-sm">
        <i class="bi bi-motorcycle text-gray-300 text-6xl"></i>
        <h6 class="text-lg font-semibold text-gray-900 mt-4">
            @if(request('status') == 'available')
                Belum ada motor yang tersedia
            @elseif(request('status') == 'rented')
                Belum ada motor yang sedang disewa
            @elseif(request('status') == 'maintenance')
                Belum ada motor dalam maintenance
            @else
                Belum ada motor yang didaftarkan
            @endif
        </h6>
        <p class="text-gray-600 mt-2">
            @if(request('status'))
                Tidak ada motor dengan status ini. Coba filter status lain.
            @else
                Mulai daftarkan motor Anda untuk disewakan dan dapatkan penghasilan tambahan
            @endif
        </p>
        @if(!request('status'))
            <a href="{{ route('pemilik.motor.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition mt-4">
                <i class="bi bi-plus-circle mr-2"></i>Daftarkan Motor Pertama
            </a>
        @else
            <a href="{{ route('pemilik.motors') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition mt-4">
                <i class="bi bi-arrow-left mr-2"></i>Lihat Semua Motor
            </a>
        @endif
    </div>
@endif

@endsection