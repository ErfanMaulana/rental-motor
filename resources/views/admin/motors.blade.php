@extends('layouts.fann')

@section('title', 'Verifikasi Motor')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')

<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Total Motor</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $motors->total() }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Perlu Verifikasi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $pendingCount ?? 0 }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Terverifikasi</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $verifiedCount ?? 0 }}</p>
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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @if($motors->count() > 0)
        @foreach($motors as $motor)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-visible motor-card hover:shadow-md transition-shadow" data-motor-id="{{ $motor->id }}">
            <!-- Motor Image -->
            <div class="relative overflow-hidden rounded-t-lg" style="aspect-ratio: 4/3;">
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
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-yellow-500 text-white">
                            <i class="bi bi-clock mr-0.5 text-[10px]"></i>Menunggu Verifikasi
                        </span>
                    @elseif($currentStatus === 'rented')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-orange-500 text-white">
                            <i class="bi bi-person-check mr-0.5 text-[10px]"></i>Disewa
                        </span>
                    @elseif($currentStatus === 'available')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-500 text-white">
                            <i class="bi bi-check-circle mr-0.5 text-[10px]"></i>Tersedia
                        </span>
                    @elseif($currentStatus === 'maintenance')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-500 text-white">
                            <i class="bi bi-tools mr-0.5 text-[10px]"></i>Maintenance
                        </span>
                    @endif
                </div>
            </div>

            <!-- Motor Info -->
            <div class="p-2">
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
                                     title="Klik untuk memperbesar - {{ $motor->document }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <small class="text-red-600 text-[9px] hidden">Error loading: {{ $motor->document }}</small>
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
            <div class="bg-gray-50 px-3 py-2.5 border-t border-gray-100">
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
                            <button type="button" 
                                    @click="open = false; showMotorDetail({{ $motor->id }})"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center transition rounded-t-lg">
                                <i class="bi bi-eye mr-2"></i>
                                Lihat Detail
                            </button>
                            
                            <!-- Verification Option -->
                            @if($motor->status === 'pending_verification')
                                <button type="button" 
                                        @click="open = false; showVerifyModal({{ $motor->id }}, '{{ $motor->brand }} {{ $motor->model }}', {{ $motor->rentalRate ? $motor->rentalRate->daily_rate : 0 }}, {{ $motor->rentalRate ? $motor->rentalRate->weekly_rate : 0 }}, {{ $motor->rentalRate ? $motor->rentalRate->monthly_rate : 0 }})"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 flex items-center transition">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    Verifikasi Motor
                                </button>
                            @else
                                <div class="px-4 py-2 text-sm text-green-600 flex items-center opacity-50 cursor-not-allowed">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    Sudah Terverifikasi
                                </div>
                            @endif
                            
                            <!-- Edit Price Option -->
                            <button type="button" 
                                    @click="open = false; showEditPriceModal({{ $motor->id }}, '{{ $motor->brand }} {{ $motor->model }}', {{ $motor->rentalRate ? $motor->rentalRate->daily_rate : 0 }}, {{ $motor->rentalRate ? $motor->rentalRate->weekly_rate : 0 }}, {{ $motor->rentalRate ? $motor->rentalRate->monthly_rate : 0 }})"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 flex items-center transition border-t border-gray-100">
                                <i class="bi bi-currency-dollar mr-2"></i>
                                Edit Harga Sewa
                            </button>
                            
                            <!-- Delete Option -->
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
                                        @click="open = false; confirmDeleteMotor({{ $motor->id }}, '{{ $motor->brand }}', '{{ $motor->model ?? '' }}', '{{ $motor->plate_number }}', '{{ $motor->owner->name }}')"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 flex items-center transition border-t border-gray-100 rounded-b-lg">
                                    <i class="bi bi-trash mr-2"></i>
                                    Hapus Motor
                                </button>
                            @else
                                <div class="px-4 py-2 text-sm text-red-600 flex items-center opacity-50 cursor-not-allowed border-t border-gray-100 rounded-b-lg"
                                     title="{{ $hasActiveBookings ? 'Motor memiliki booking aktif' : 'Motor memiliki riwayat booking' }}">
                                    <i class="bi bi-shield-exclamation mr-2"></i>
                                    Tidak Dapat Dihapus
                                </div>
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
             class="inline-block w-[600px] my-2 overflow-hidden text-left align-middle transition-all transform bg-white rounded shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-2.5 py-1.5 bg-blue-600 text-white">
                <h3 class="text-[11px] font-semibold">
                    <i class="bi bi-motorcycle mr-1 text-[10px]"></i>Detail Motor
                </h3>
                <button @click="open = false" class="text-white hover:text-gray-200">
                    <i class="bi bi-x-lg text-[10px]"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div id="motorDetailContent" class="p-2.5 text-xs">
                <!-- Content will be loaded here by JavaScript -->
            </div>
            
            <!-- Footer -->
            <div class="px-2.5 py-1.5 bg-gray-50 border-t border-gray-200">
                <button @click="open = false" class="w-full px-2.5 py-1 text-[10px] font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition">
                    <i class="bi bi-x-circle mr-0.5 text-[9px]"></i>Tutup
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
     @close-delete-motor.window="open = false"
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
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-800">
                    <i class="bi bi-exclamation-circle mr-1"></i>
                    Perhatian: Data motor, foto, dokumen, dan semua informasi terkait akan dihapus permanen!
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify Motor Modal -->
<div x-data="{ open: false, motorId: null, motorName: '', dailyRate: 0, weeklyRate: 0, monthlyRate: 0 }" 
     x-show="open" 
     @open-verify-motor.window="open = true; motorId = $event.detail.motorId; motorName = $event.detail.motorName; dailyRate = $event.detail.dailyRate; weeklyRate = $event.detail.weeklyRate; monthlyRate = $event.detail.monthlyRate"
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
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <form :action="`/admin/motors/${motorId}/verify`" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="flex items-center mb-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifikasi Motor</h3>
                        <p class="text-sm text-gray-600" x-text="motorName"></p>
                        <p class="text-xs text-gray-500 mt-2">Setelah motor diverifikasi dan harga ditetapkan, motor akan tersedia untuk disewa oleh penyewa.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tarif Harian <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       id="dailyRateInput"
                                       name="daily_rate" 
                                       :value="dailyRate"
                                       min="10000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Contoh: 50000"
                                       @input="calculateRates"
                                       required>
                            </div>
                            <small class="text-gray-500 text-xs">Minimal Rp 10.000</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Mingguan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       id="weeklyRateInput"
                                       name="weekly_rate" 
                                       :value="weeklyRate"
                                       min="50000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Otomatis dari tarif harian"
                                       readonly>
                            </div>
                            <small class="text-gray-500 text-xs">Auto: diskon 10% dari 7x tarif harian</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Bulanan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       id="monthlyRateInput"
                                       name="monthly_rate" 
                                       :value="monthlyRate"
                                       min="200000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Otomatis dari tarif harian"
                                       readonly>
                            </div>
                            <small class="text-gray-500 text-xs">Auto: diskon 20% dari 30x tarif harian</small>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex gap-3">
                    <button type="button" 
                            @click="open = false"
                            class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="bi bi-x-lg mr-1"></i>Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="bi bi-check-circle mr-1"></i>Verifikasi Motor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4 mb-2">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-exclamation-triangle text-yellow-600 text-lg mt-0.5"></i>
                        <p class="text-sm text-yellow-800">
                            <strong class="font-semibold">Peringatan:</strong> Motor yang dihapus akan menghilangkan semua data terkait termasuk booking, pembayaran, dan riwayat lainnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Price Modal -->
<div x-data="{ open: false, motorId: null, motorName: '', dailyRate: 0, weeklyRate: 0, monthlyRate: 0 }" 
     x-show="open" 
     @open-edit-price-modal.window="open = true; motorId = $event.detail.motorId; motorName = $event.detail.motorName; dailyRate = $event.detail.dailyRate; weeklyRate = $event.detail.weeklyRate; monthlyRate = $event.detail.monthlyRate"
     @keydown.escape.window="open = false"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto"
     id="editPriceModal">
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
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <form method="POST" id="editPriceForm" onsubmit="return submitEditPrice(event);">
                @csrf
                
                <input type="hidden" id="editMotorId" name="motor_id" x-model="motorId">
                
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="flex items-center mb-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                            <i class="bi bi-currency-dollar text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Edit Harga Sewa Motor</h3>
                        <p class="text-sm text-gray-600" x-text="motorName"></p>
                        <p class="text-xs text-gray-500 mt-2">Ubah harga sewa motor untuk tarif harian, mingguan, dan bulanan</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tarif Harian <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       id="editDailyRateInput"
                                       name="daily_rate"
                                       x-model="dailyRate"
                                       min="10000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                       placeholder="Contoh: 50000"
                                       @input="weeklyRate = Math.floor(dailyRate * 7 * 0.9); monthlyRate = Math.floor(dailyRate * 30 * 0.8)"
                                       required>
                            </div>
                            <small class="text-gray-500 text-xs">Minimal Rp 10.000</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Mingguan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       id="editWeeklyRateInput"
                                       name="weekly_rate"
                                       x-model="weeklyRate"
                                       min="50000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-gray-50"
                                       placeholder="Otomatis dari tarif harian"
                                       readonly>
                            </div>
                            <small class="text-gray-500 text-xs">Auto: diskon 10% dari 7x tarif harian</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Bulanan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" 
                                       id="editMonthlyRateInput"
                                       name="monthly_rate"
                                       x-model="monthlyRate"
                                       min="200000"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-gray-50"
                                       placeholder="Otomatis dari tarif harian"
                                       readonly>
                            </div>
                            <small class="text-gray-500 text-xs">Auto: diskon 20% dari 30x tarif harian</small>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex gap-3">
                    <button type="button" 
                            @click="open = false"
                            class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="bi bi-x-lg mr-1"></i>Batal
                    </button>
                    <button type="submit" 
                            id="editPriceSubmitBtn"
                            class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <i class="bi bi-save mr-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.motor-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border: 1px solid #e2e8f0 !important;
}

.motor-card:hover {
    transform: translateY(-10px) scale(1.02) !important;
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15) !important;
    border-color: #cbd5e1 !important;
}

.motor-card:hover .object-cover {
    transform: scale(1.1) !important;
}

.motor-card .object-cover {
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
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

// Function to show verify modal
function showVerifyModal(motorId, motorName, dailyRate, weeklyRate, monthlyRate) {
    window.dispatchEvent(new CustomEvent('open-verify-motor', {
        detail: { motorId, motorName, dailyRate, weeklyRate, monthlyRate }
    }));
    
    // Wait for modal to be rendered, then attach event listener
    setTimeout(() => {
        const dailyRateInput = document.getElementById('dailyRateInput');
        if (dailyRateInput) {
            // Trigger calculation on initial load
            calculateRatesFromDaily(dailyRateInput.value);
            
            // Add event listener for real-time calculation
            dailyRateInput.addEventListener('input', function(e) {
                calculateRatesFromDaily(e.target.value);
            });
        }
    }, 100);
}

// Function to calculate weekly and monthly rates based on daily rate
function calculateRatesFromDaily(dailyRate) {
    const daily = parseFloat(dailyRate) || 0;
    
    if (daily > 0) {
        // Calculate weekly rate: 7 days with 10% discount
        const weeklyRate = Math.round(daily * 7 * 0.9);
        
        // Calculate monthly rate: 30 days with 20% discount
        const monthlyRate = Math.round(daily * 30 * 0.8);
        
        // Update the input fields
        const weeklyInput = document.getElementById('weeklyRateInput');
        const monthlyInput = document.getElementById('monthlyRateInput');
        
        if (weeklyInput) weeklyInput.value = weeklyRate;
        if (monthlyInput) monthlyInput.value = monthlyRate;
    }
}

// Function to show edit price modal
function showEditPriceModal(motorId, motorName, dailyRate, weeklyRate, monthlyRate) {
    window.dispatchEvent(new CustomEvent('open-edit-price-modal', {
        detail: { motorId, motorName, dailyRate, weeklyRate, monthlyRate }
    }));
}

// Function to submit edit price form
function submitEditPrice(event) {
    event.preventDefault();
    
    const form = event.target;
    const motorId = document.getElementById('editMotorId').value;
    const dailyRate = document.getElementById('editDailyRateInput').value;
    const weeklyRate = document.getElementById('editWeeklyRateInput').value;
    const monthlyRate = document.getElementById('editMonthlyRateInput').value;
    
    if (!dailyRate || dailyRate < 10000) {
        showAlert('error', 'Tarif harian minimal Rp 10.000!');
        return false;
    }
    
    const submitBtn = document.getElementById('editPriceSubmitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Menyimpan...';
    
    // Send update request
    fetch(`/admin/motors/${motorId}/update-price`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            daily_rate: parseFloat(dailyRate),
            weekly_rate: parseFloat(weeklyRate),
            monthly_rate: parseFloat(monthlyRate)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Harga sewa berhasil diperbarui!');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Gagal memperbarui harga sewa!');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memperbarui harga!');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
    
    return false;
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
    fetch(`/admin/motors/${motorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('success', 'Motor berhasil dihapus!');
            
            // Close modal
            window.dispatchEvent(new CustomEvent('close-delete-motor'));
            
            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Gagal menghapus motor!');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menghapus motor!');
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