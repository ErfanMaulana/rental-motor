@extends('layouts.fann')

@section('title', 'Verifikasi Motor')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
        <i class="bi bi-motorcycle text-blue-600 mr-3"></i>
        Verifikasi Motor
    </h1>
    <p class="text-sm text-gray-500 mt-1 ml-11">Kelola dan verifikasi motor yang didaftarkan pemilik</p>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Motor</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $motors->total() }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Perlu Verifikasi</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ $pendingCount ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Terverifikasi</p>
            <p class="text-2xl font-semibold text-green-600">{{ $verifiedCount ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Tersedia</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $motors->where('status', 'available')->count() }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form id="motorFilterForm" method="GET" action="{{ route('admin.motors') }}" class="flex flex-wrap gap-3">
        <select onchange="document.getElementById('motorFilterForm').submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="status">
            <option value="">Semua Status</option>
            <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
        <select onchange="document.getElementById('motorFilterForm').submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="cc">
            <option value="">Semua CC</option>
            <option value="100" {{ request('cc') == '100' ? 'selected' : '' }}>100cc</option>
            <option value="125" {{ request('cc') == '125' ? 'selected' : '' }}>125cc</option>
            <option value="150" {{ request('cc') == '150' ? 'selected' : '' }}>150cc</option>
            <option value="250" {{ request('cc') == '250' ? 'selected' : '' }}>250cc</option>
            <option value="500" {{ request('cc') == '500' ? 'selected' : '' }}>500cc</option>
        </select>
        <input type="text" class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500" name="search" value="{{ request('search') }}" placeholder="Cari brand atau plat nomor...">
        <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" type="submit">
            <i class="bi bi-search"></i>
        </button>
        <a href="{{ route('admin.motors') }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" title="Reset">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </form>
</div>

<!-- Motors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @if($motors->count() > 0)
        @foreach($motors as $motor)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden motor-card" data-motor-id="{{ $motor->id }}">
            <!-- Motor Image -->
            <div class="relative">
                @if($motor->photo)
                    <img src="{{ Storage::url($motor->photo) }}" 
                         class="w-full h-64 object-cover" 
                         alt="{{ $motor->brand }}">
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-motorcycle text-gray-400" style="font-size: 4rem;"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-0 right-0 m-3">
                    @if($motor->status === 'pending_verification')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500 text-white">
                            <i class="bi bi-clock mr-1"></i>Menunggu Verifikasi
                        </span>
                    @elseif($motor->status === 'available')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                            <i class="bi bi-check-circle mr-1"></i>Tersedia
                        </span>
                    @elseif($motor->status === 'rented')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-600 text-white">
                            <i class="bi bi-person-check mr-1"></i>Sedang Disewa
                        </span>
                    @elseif($motor->status === 'maintenance')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-500 text-white">
                            <i class="bi bi-tools mr-1"></i>Maintenance
                        </span>
                    @endif
                </div>
            </div>

            <!-- Motor Info -->
            <div class="p-4">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center mb-2">
                    <i class="bi bi-motorcycle text-blue-600 mr-2"></i>
                    {{ $motor->brand }}
                </h5>
                <p class="text-gray-600 text-sm mb-3">
                    <i class="bi bi-gear mr-1"></i>{{ $motor->type_cc }}
                    <span class="ml-3">
                        <i class="bi bi-credit-card mr-1"></i>{{ $motor->plate_number }}
                    </span>
                </p>
                
                <!-- Owner Info -->
                <div class="mb-3 border-t border-gray-100 pt-3">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-person-circle mr-2 text-gray-400"></i>
                        <div>
                            <div class="font-medium text-sm text-gray-900">{{ $motor->owner->name }}</div>
                            <small class="text-gray-500 text-xs">{{ $motor->owner->email }}</small>
                        </div>
                    </div>
                    
                    <!-- Document Photo -->
                    @if($motor->document)
                        <div class="flex items-center mt-2">
                            <i class="bi bi-file-earmark-text mr-2 text-gray-400"></i>
                            <div>
                                <small class="text-gray-500 text-xs block">Dokumen Motor:</small>
                                <img src="{{ Storage::url($motor->document) }}" 
                                     alt="Dokumen Motor" 
                                     class="rounded border border-gray-200 cursor-pointer hover:border-blue-500 transition"
                                     style="width: 60px; height: 40px; object-fit: cover;"
                                     onclick="showDocumentPreview('{{ Storage::url($motor->document) }}')"
                                     title="Klik untuk memperbesar - {{ $motor->document }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <small class="text-red-600 text-xs hidden">Error loading: {{ $motor->document }}</small>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center mt-2">
                            <i class="bi bi-file-earmark-x mr-2 text-gray-400"></i>
                            <small class="text-gray-500 text-xs">Dokumen belum diupload</small>
                        </div>
                    @endif
                </div>

                <!-- Rental Rates -->
                @if($motor->rentalRate)
                    <div class="mb-3 border-t border-gray-100 pt-3">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <small class="text-gray-500 text-xs block">Harian</small>
                                <div class="font-semibold text-blue-600 text-xs">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <small class="text-gray-500 text-xs block">Mingguan</small>
                                <div class="font-semibold text-blue-600 text-xs">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <small class="text-gray-500 text-xs block">Bulanan</small>
                                <div class="font-semibold text-blue-600 text-xs">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card Footer with Action Buttons -->
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <small class="text-gray-500 text-xs">
                        <i class="bi bi-calendar mr-1"></i>
                        {{ $motor->created_at->format('d M Y') }}
                    </small>
                    <div class="flex gap-2">
                        <!-- Detail Button -->
                        <button type="button" 
                                class="px-3 py-1.5 text-xs font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition"
                                data-action="show-detail"
                                data-motor-id="{{ $motor->id }}"
                                    onclick="showMotorDetail({{ $motor->id }})"
                                    title="Lihat Detail Motor">
                                <i class="bi bi-eye mr-1"></i>Detail
                            </button>
                            
                            <!-- Verification Button (only for pending motors) -->
                            @if($motor->status === 'pending_verification')
                                <button type="button" 
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition"
                                        data-action="verify-motor"
                                        data-motor-id="{{ $motor->id }}"
                                        onclick="directVerifyMotor({{ $motor->id }})"
                                        title="Verifikasi Motor">
                                    <i class="bi bi-check-circle mr-1"></i>Verifikasi
                                </button>
                            @else
                                <span class="px-3 py-1.5 text-xs font-medium text-green-600 border border-green-600 rounded-lg opacity-50 cursor-not-allowed">
                                    <i class="bi bi-check-circle mr-1"></i>Terverifikasi
                                </span>
                            @endif
                            
                            <!-- Delete Button -->
                            @php
                                $hasActiveBookings = $motor->bookings()
                                    ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
                                    ->count() > 0;
                                $hasCompletedBookings = $motor->bookings()
                                    ->where('status', 'completed')
                                    ->count() > 0;
                                $canDelete = !$hasActiveBookings && !$hasCompletedBookings;
                            @endphp
                            
                            @if($canDelete)
                                <button type="button" 
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
                                        data-action="delete-motor"
                                        data-motor-id="{{ $motor->id }}"
                                        data-motor-brand="{{ $motor->brand }}"
                                        data-motor-model="{{ $motor->model }}"
                                        data-motor-plate="{{ $motor->license_plate }}"
                                        data-motor-owner="{{ $motor->owner->name }}"
                                        onclick="confirmDeleteMotor({{ $motor->id }}, '{{ $motor->brand }}', '{{ $motor->model }}', '{{ $motor->license_plate }}', '{{ $motor->owner->name }}')"
                                        title="Hapus Motor">
                                    <i class="bi bi-trash mr-1"></i>Hapus
                                </button>
                            @else
                                <button type="button" 
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-600 rounded-lg opacity-50 cursor-not-allowed"
                                        title="{{ $hasActiveBookings ? 'Motor memiliki booking aktif' : 'Motor memiliki riwayat booking' }}">
                                    <i class="bi bi-shield-exclamation mr-1"></i>Tidak Dapat Dihapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-span-full flex flex-col items-center justify-center py-16">
            <i class="bi bi-motorcycle text-gray-300" style="font-size: 5rem;"></i>
            <h4 class="mt-4 text-lg font-medium text-gray-700">Tidak ada motor ditemukan</h4>
            <p class="text-sm text-gray-500 mt-1">Coba ubah filter pencarian Anda</p>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($motors->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $motors->appends(request()->except('page'))->links() }}
    </div>
@endif

<!-- Motor Detail Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-motor-detail.window="open = true"
     @keydown.escape.window="open = false"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     id="motorDetailModal">
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
             class="inline-block w-full max-w-6xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-blue-600 text-white">
                <h3 class="text-lg font-medium">
                    <i class="bi bi-motorcycle mr-2"></i>Detail Motor
                </h3>
                <button @click="open = false" class="text-white hover:text-gray-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-4">
                <div id="motorDetailContent">
                    <!-- Content will be loaded here by JavaScript -->
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50">
                <button @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    <i class="bi bi-x-circle mr-2"></i>Tutup
                </button>
                <button type="button" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition hidden" id="verifyMotorFromModal">
                    <i class="bi bi-check-circle mr-2"></i>Verifikasi Motor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-document-preview.window="open = true"
     @keydown.escape.window="open = false"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     id="documentPreviewModal">
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

<!-- Delete Motor Confirmation Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-delete-motor.window="open = true"
     @keydown.escape.window="open = false"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     id="deleteMotorModal">
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
                    <p class="text-gray-600 mt-2 mb-3">Tindakan ini tidak dapat dibatalkan dan akan menghapus:</p>
                </div>
                
                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <div class="flex mb-2">
                        <div class="w-1/3 text-gray-600 text-sm">Brand/Model:</div>
                        <div class="w-2/3 font-bold" id="deleteMotorBrand">-</div>
                    </div>
                    <div class="flex mb-2">
                        <div class="w-1/3 text-gray-600 text-sm">Plat Nomor:</div>
                        <div class="w-2/3 font-bold" id="deleteMotorPlate">-</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-gray-600 text-sm">Pemilik:</div>
                        <div class="w-2/3 font-bold" id="deleteMotorOwner">-</div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <i class="bi bi-exclamation-triangle text-yellow-600 mr-2"></i>
                    <small>
                        <strong>Peringatan:</strong> Motor yang dihapus akan menghilangkan semua data terkait 
                        termasuk booking, pembayaran, dan riwayat lainnya.
                    </small>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50">
                <button @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    <i class="bi bi-x-circle mr-1"></i>Batal
                </button>
                <button type="button" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition" id="confirmDeleteMotor">
                    <i class="bi bi-trash mr-1"></i>Ya, Hapus Motor
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.motor-card {
    transition: all 0.3s ease;
}

.motor-card:hover {
    transform: translateY(-5px);
}

.cursor-pointer {
    cursor: pointer;
}

.img-thumbnail:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.2s ease;
}

#documentPreviewImage {
    transition: all 0.3s ease;
}
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.custom-pagination .page-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: white;
    border-color: #0d6efd;
}

.custom-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border-color: #0d6efd;
    color: white;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    transform: translateY(-1px);
}

.custom-pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-wrapper {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.quick-jump {
    display: flex;
    align-items: center;
}

.quick-jump .input-group {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.quick-jump .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.quick-jump .btn {
    border-color: #0d6efd;
    color: #0d6efd;
}

.quick-jump .btn:hover {
    background-color: #0d6efd;
    color: white;
    transform: translateY(-1px);
}

.motor-card {
    transition: all 0.3s ease;
}

.motor-card:hover {
    transform: translateY(-5px);
}

.cursor-pointer {
    cursor: pointer;
}

.img-thumbnail:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.2s ease;
}

#documentPreviewImage {
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
console.log('Motor admin page loaded');

// Function to show document preview
function showDocumentPreview(imageUrl) {
    const previewImage = document.getElementById('documentPreviewImage');
    previewImage.src = imageUrl;
    window.dispatchEvent(new CustomEvent('open-document-preview'));
}

// Delete Motor Functions
let motorToDelete = null;

function confirmDeleteMotor(motorId, brand, model, plate, owner) {
    motorToDelete = motorId;
    
    // Update modal content
    document.getElementById('deleteMotorBrand').textContent = `${brand} ${model}`;
    document.getElementById('deleteMotorPlate').textContent = plate;
    document.getElementById('deleteMotorOwner').textContent = owner;
    
    // Show modal using Alpine.js
    window.dispatchEvent(new CustomEvent('open-delete-motor'));
}

function deleteMotor(motorId) {
    const confirmBtn = document.getElementById('confirmDeleteMotor');
    const originalText = confirmBtn.innerHTML;
    
    // Show loading state
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Menghapus...';
    
    // Send delete request
    fetch(`{{ route('admin.motors') }}/${motorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('success', 'Motor berhasil dihapus!');
            
            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Gagal menghapus motor!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menghapus motor!');
    })
    .finally(() => {
        // Reset button state
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalText;
    });
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const bgClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const iconClass = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
    
    const alertHtml = `
        <div class="custom-alert fixed top-5 right-5 z-[9999] min-w-[300px] ${bgClass} text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="${iconClass} mr-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    `;
    
    // Add to body
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.custom-alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Handle Enter key in page jump input
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to existing document thumbnails
    const docThumbnails = document.querySelectorAll('.img-thumbnail[onclick*="showDocumentPreview"]');
    docThumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Handle delete motor confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteMotor');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (motorToDelete) {
                deleteMotor(motorToDelete);
            }
        });
    }
    
});
</script>
<script src="{{ asset('js/simple-motor-verification.js') }}"></script>
@endpush