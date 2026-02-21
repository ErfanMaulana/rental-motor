@extends('layouts.fann')

@section('title', 'Motor Saya')

@php
use Illuminate\Support\Facades\Storage;
@endphp

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
            <input type="text" 
                   class="flex-1 md:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari motor...">
            <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    name="status">
                <option value="">Semua Status</option>
                <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<!-- Motors List -->
@if($motors->count() > 0)
    <div style="display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 1.5rem !important;">
        @foreach($motors as $motor)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-200" style="display: block !important;">
            <!-- Motor Image -->
            <div class="relative overflow-hidden rounded-t-lg" style="height: 200px; min-height: 200px;">
                @if($motor->photo)
                    <img src="{{ Storage::url($motor->photo) }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $motor->brand }}">
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-motorcycle text-gray-400" style="font-size: 1.5rem;"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-0 right-0 m-2">
                    @php
                        $currentStatus = $motor->getCurrentStatus();
                    @endphp
                    
                    @if($currentStatus === 'pending_verification')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">
                            <i class="bi bi-clock mr-1"></i>Menunggu Verifikasi
                        </span>
                    @elseif($currentStatus === 'rented')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-300">
                            <i class="bi bi-person-check mr-1"></i>Disewa
                        </span>
                    @elseif($currentStatus === 'available')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-300">
                            <i class="bi bi-check-circle mr-1"></i>Tersedia
                        </span>
                    @elseif($currentStatus === 'maintenance')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-300">
                            <i class="bi bi-tools mr-1"></i>Maintenance
                        </span>
                    @endif
                </div>
            </div>

            <!-- Motor Info -->
            <div class="p-3 flex-grow flex flex-col">
                <h5 class="text-sm font-semibold text-gray-900 flex items-center mb-1">
                    <i class="bi bi-motorcycle text-blue-600 mr-1 text-sm"></i>
                    {{ $motor->model }}
                </h5>
                <p class="text-gray-600 text-[10px] mb-1.5">
                    <i class="bi bi-tag mr-0.5 text-[10px]"></i>{{ $motor->brand }}
                    <span class="ml-2">
                        <i class="bi bi-credit-card mr-0.5 text-[10px]"></i>{{ $motor->plate_number }}
                    </span>
                    <span class="ml-2">
                        <i class="bi bi-gear mr-0.5 text-[10px]"></i>{{ $motor->type_cc }}
                    </span>
                </p>
                
                <!-- Owner Info -->
                <div class="mb-1.5 border-t border-gray-100 pt-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <!-- Owner Details -->
                        <div class="flex items-center flex-1">
                            <i class="bi bi-person-circle mr-1 text-gray-400 text-xs"></i>
                            <div>
                                <div class="font-medium text-[10px] text-gray-900">{{ $motor->owner->name }}</div>
                                <small class="text-gray-500 text-[9px]">{{ $motor->owner->email }}</small>
                            </div>
                        </div>
                        
                        <!-- Document Photo -->
                        @if($motor->document)
                            <div class="flex items-center gap-1">
                                <div class="text-right">
                                    <small class="text-gray-500 text-[9px] block">Dokumen:</small>
                                    <i class="bi bi-file-earmark-text text-gray-400 text-[8px]"></i>
                                </div>
                                <img src="{{ Storage::url($motor->document) }}" 
                                     alt="Dokumen Motor" 
                                     class="rounded border border-gray-200 cursor-pointer hover:border-blue-500 transition"
                                     style="width: 45px; height: 30px; object-fit: cover;"
                                     onclick="showDocumentPreview('{{ Storage::url($motor->document) }}')"
                                     title="Klik untuk memperbesar">
                            </div>
                        @else
                            <div class="flex items-center gap-1">
                                <i class="bi bi-file-earmark-x text-gray-400 text-xs"></i>
                                <small class="text-gray-500 text-[9px]">Tidak ada</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rental Rates -->
                @if($motor->rentalRate)
                    <div class="mb-1.5 border-t border-gray-100 pt-1.5">
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

            <!-- Card Footer with Action Buttons -->
            <div class="bg-gray-50 px-3 py-2.5 border-t border-gray-100 mt-auto">
                <div class="flex justify-between items-center">
                    <small class="text-gray-500 text-[10px]">
                        <i class="bi bi-calendar mr-1 text-[10px]"></i>
                        {{ $motor->created_at->format('d M Y') }}
                    </small>
                    
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

<!-- Document Preview Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-document-preview.window="open = true"
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
             class="fixed inset-0 transition-opacity bg-black bg-opacity-75"
             @click="open = false"></div>

        <!-- Modal panel -->
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-900 rounded-lg shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-gray-800 text-white">
                <h3 class="text-lg font-medium">
                    <i class="bi bi-file-earmark-text mr-2"></i>Preview Dokumen Motor
                </h3>
                <button @click="open = false" class="text-white hover:text-gray-300">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-0 text-center bg-gray-900">
                <img id="documentPreviewImage" 
                     src="" 
                     alt="Dokumen Motor" 
                     class="w-full"
                     style="max-height: 80vh; object-fit: contain;">
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end px-6 py-4 bg-gray-800">
                <button @click="open = false" class="px-4 py-2 text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    <i class="bi bi-x-circle mr-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Function to show document preview
function showDocumentPreview(imageUrl) {
    const previewImage = document.getElementById('documentPreviewImage');
    previewImage.src = imageUrl;
    window.dispatchEvent(new CustomEvent('open-document-preview'));
}
</script>
@endpush