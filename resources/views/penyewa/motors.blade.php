@extends('layouts.fann')

@section('title', 'Daftar Motor - Penyewa')

@section('content')
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Daftar Motor</h1>
    <p class="text-gray-600 mt-1">Pilih motor yang ingin Anda sewa</p>
</div>

<!-- Verification Status Alert -->
@if(!$isVerified)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
    <div class="flex items-center">
        <i class="bi bi-exclamation-triangle-fill text-yellow-600 text-xl mr-3"></i>
        <div>
            <strong class="text-yellow-800">Akun Belum Diverifikasi:</strong>
            <p class="text-yellow-700 text-sm mt-1">Anda tidak dapat menyewa motor hingga akun diverifikasi oleh admin.</p>
        </div>
    </div>
</div>
@endif

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400"></i>
                </div>
                <input type="text" 
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       id="searchMotor" 
                       placeholder="Cari brand, model, tahun...">
            </div>
        </div>
        <div>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filterBrand">
                <option value="">Semua Merek</option>
                @foreach($motors->pluck('brand')->unique() as $brand)
                    <option value="{{ $brand }}">{{ $brand }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filterType">
                <option value="">Semua Tipe CC</option>
                <option value="110cc">110cc</option>
                <option value="125cc">125cc</option>
                <option value="150cc">150cc</option>
                <option value="160cc">160cc</option>
                <option value="250cc">250cc</option>
            </select>
        </div>
        <div>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="sortBy">
                <option value="newest">Terbaru</option>
                <option value="price_low">Harga Terendah</option>
                <option value="price_high">Harga Tertinggi</option>
                <option value="brand">Merek A-Z</option>
            </select>
        </div>
    </div>
</div>

<!-- Motor Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="motorContainer">
    @forelse($motors as $motor)
        <div class="motor-card bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden flex flex-col" 
             data-brand="{{ $motor->brand }}" 
             data-type="{{ $motor->type_cc }}" 
             data-name="{{ strtolower($motor->brand . ' ' . $motor->model . ' ' . $motor->year . ' ' . $motor->plate_number) }}">
            <div class="relative">
                @if($motor->photo)
                    <img src="{{ Storage::url($motor->photo) }}" 
                         class="w-full h-48 object-cover" 
                         alt="{{ $motor->brand }} {{ $motor->model }}">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-scooter text-gray-300 text-6xl"></i>
                    </div>
                @endif
                
                <!-- Badges -->
                <div class="absolute top-2 left-2 flex gap-1">
                    <span class="px-2 py-0.5 text-xs font-semibold bg-blue-600 text-white rounded">{{ $motor->type_cc }}</span>
                    <span class="px-2 py-0.5 text-xs font-semibold bg-green-600 text-white rounded">{{ ucfirst($motor->status) }}</span>
                </div>
            </div>

            <div class="p-4 flex flex-col flex-1">
                <!-- Motor Name -->
                <h5 class="text-lg font-bold text-gray-900 mb-2">{{ $motor->brand }} {{ $motor->model }}</h5>
                
                <!-- Motor Info Grid -->
                <div class="space-y-1 mb-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-calendar3 w-4"></i>
                        <span class="ml-2">{{ $motor->year }}</span>
                        <i class="bi bi-palette ml-4 w-4"></i>
                        <span class="ml-2">{{ $motor->color }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-credit-card-2-front w-4"></i>
                        <span class="ml-2">{{ $motor->plate_number }}</span>
                    </div>
                </div>

                <!-- Condition -->
                @if($motor->description)
                    <p class="text-xs text-gray-500 mb-3 line-clamp-2">
                        {{ $motor->description }}
                    </p>
                @endif

                <!-- Pricing -->
                <div class="mb-3">
                    @if($motor->rentalRate)
                        <div class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span>
                        </div>
                    @else
                        <div class="text-gray-500 text-sm">
                            <i class="bi bi-exclamation-triangle mr-1"></i>Harga belum ditentukan
                        </div>
                    @endif
                </div>

                <!-- Owner Info -->
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <i class="bi bi-person-fill text-gray-400"></i>
                    <span class="ml-1">Pemilik: <span class="font-medium text-gray-900">{{ $motor->owner->name }}</span></span>
                </div>

                <!-- Rating -->
                <div class="mb-4">
                    @php
                        $avgRating = $motor->getAverageRating();
                        $totalRatings = $motor->getTotalRatings();
                    @endphp
                    
                    @if($totalRatings > 0)
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $avgRating ? '-fill' : '' }} text-yellow-400 text-sm"></i>
                            @endfor
                            <span class="text-xs text-gray-600 ml-2">({{ number_format($avgRating, 1) }}/5 • {{ $totalRatings }} ulasan)</span>
                        </div>
                    @else
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="bi bi-star mr-1"></i>Belum ada rating
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-auto space-y-2">
                    <button type="button" 
                            class="w-full px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 hover:bg-blue-50 rounded-lg transition" 
                            onclick="showMotorDetail({{ $motor->id }})">
                        <i class="bi bi-eye mr-1"></i>Lihat Detail
                    </button>
                    @if($motor->rentalRate)
                        @if($isVerified)
                            <a href="{{ route('penyewa.booking.form', $motor->id) }}" 
                               class="block w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition text-center">
                                <i class="bi bi-calendar-plus mr-1"></i>Sewa Sekarang
                            </a>
                        @else
                            <button class="w-full px-4 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed" disabled title="Akun belum diverifikasi">
                                <i class="bi bi-lock mr-1"></i>Perlu Verifikasi
                            </button>
                        @endif
                    @else
                        <button class="w-full px-4 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed" disabled>
                            <i class="bi bi-x-circle mr-1"></i>Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-12 bg-white rounded-lg">
                <i class="bi bi-scooter text-gray-300 text-6xl"></i>
                <h4 class="mt-4 text-xl font-semibold text-gray-600">Tidak ada motor yang tersedia</h4>
                <p class="text-gray-500 mt-2">Silakan cek kembali nanti atau ubah filter pencarian.</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($motors->hasPages())
    <div class="flex justify-center mt-6">
        {{ $motors->links() }}
    </div>
@endif

<!-- Motor Detail Modal -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-motor-detail.window="open = true"
     @close-motor-detail.window="open = false"
     @keydown.escape.window="open = false"
     x-cloak
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
            <div id="motorDetailContent" class="p-2.5 max-h-[320px] overflow-y-auto text-xs">
                <div class="flex justify-center py-6">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-4 border-blue-600 border-t-transparent"></div>
                        <p class="mt-2 text-gray-600 text-xs">Memuat...</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-2.5 py-1.5 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <button @click="open = false" class="px-2.5 py-1 text-[10px] font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition">
                    <i class="bi bi-x-circle mr-0.5 text-[9px]"></i>Tutup
                </button>
                <div id="bookingButtonContainer"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMotor');
    const brandFilter = document.getElementById('filterBrand');
    const typeFilter = document.getElementById('filterType');
    const sortBy = document.getElementById('sortBy');

    // Search and Filter functionality
    function filterMotors() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedBrand = brandFilter.value;
        const selectedType = typeFilter.value;
        const sortValue = sortBy.value;
        
        let motorCards = Array.from(document.querySelectorAll('.motor-card'));
        
        // Filter
        motorCards.forEach(card => {
            const name = card.dataset.name;
            const brand = card.dataset.brand;
            const type = card.dataset.type;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesBrand = !selectedBrand || brand === selectedBrand;
            const matchesType = !selectedType || type === selectedType;
            
            if (matchesSearch && matchesBrand && matchesType) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Sort visible cards
        const visibleCards = motorCards.filter(card => card.style.display !== 'none');
        const container = document.getElementById('motorContainer');
        
        if (sortValue === 'brand') {
            visibleCards.sort((a, b) => a.dataset.brand.localeCompare(b.dataset.brand));
        } else if (sortValue === 'price_low' || sortValue === 'price_high') {
            // Price sorting would need additional data attributes
            // For now, keep original order
        }
        
        // Reorder in DOM
        visibleCards.forEach(card => {
            container.appendChild(card);
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterMotors);
    brandFilter.addEventListener('change', filterMotors);
    typeFilter.addEventListener('change', filterMotors);
    sortBy.addEventListener('change', filterMotors);
});

// Show motor detail in modal
function showMotorDetail(motorId) {
    console.log('showMotorDetail called with ID:', motorId);
    
    const content = document.getElementById('motorDetailContent');
    const bookingContainer = document.getElementById('bookingButtonContainer');
    
    // Reset content with loading state
    content.innerHTML = `
        <div class="flex justify-center py-12">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
                <p class="mt-4 text-gray-600">Memuat detail motor...</p>
            </div>
        </div>
    `;
    bookingContainer.innerHTML = '';
    
    // Show modal using Alpine
    window.dispatchEvent(new CustomEvent('open-motor-detail'));
    
    // Fetch motor detail
    fetch(`/penyewa/motors/${motorId}/detail-ajax`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Motor data received:', data);
            const motor = data.motor;
            
            // Build content HTML with compact style like admin
            content.innerHTML = `
                <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                    <!-- Left Column: Image -->
                    <div>
                        ${motor.photo ? 
                            `<img src="/storage/${motor.photo}" class="w-full h-[180px] object-cover rounded border border-gray-200" alt="${motor.brand} ${motor.model}">` :
                            `<div class="w-full h-[180px] bg-gray-100 rounded flex items-center justify-center border border-gray-200">
                                <i class="bi bi-scooter text-gray-300 text-3xl"></i>
                             </div>`
                        }
                    </div>
                    
                    <!-- Right Column: Info -->
                    <div class="space-y-1">
                        <!-- Motor Name & Badges -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-1 rounded">
                            <h4 class="text-[11px] font-bold text-gray-900 mb-0.5">${motor.brand} ${motor.model}</h4>
                            <div class="flex gap-0.5 flex-wrap">
                                <span class="px-1 py-0.5 text-[8px] font-semibold rounded-full bg-blue-600 text-white">${motor.type_cc}</span>
                                <span class="px-1 py-0.5 text-[8px] font-semibold rounded-full ${motor.status === 'available' ? 'bg-green-600' : 'bg-gray-400'} text-white">
                                    ${motor.status === 'available' ? 'Tersedia' : motor.status}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Basic Info Grid -->
                        <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                            <div class="bg-gray-50 p-1.5 rounded">
                                <div class="text-gray-500">Tahun</div>
                                <div class="font-semibold text-gray-900">${motor.year}</div>
                            </div>
                            <div class="bg-gray-50 p-1.5 rounded">
                                <div class="text-gray-500">Warna</div>
                                <div class="font-semibold text-gray-900">${motor.color}</div>
                            </div>
                            <div class="bg-gray-50 p-1.5 rounded">
                                <div class="text-gray-500">Plat Nomor</div>
                                <div class="font-semibold text-gray-900">${motor.plate_number}</div>
                            </div>
                            <div class="bg-gray-50 p-1.5 rounded">
                                <div class="text-gray-500">Pemilik</div>
                                <div class="font-semibold text-gray-900">${motor.owner.name}</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        ${motor.description ? `
                            <div class="bg-gray-50 p-1.5 rounded text-[10px]">
                                <div class="text-gray-500 mb-0.5">Deskripsi</div>
                                <div class="text-gray-700">${motor.description}</div>
                            </div>
                        ` : ''}
                        
                        <!-- Rental Rates -->
                        ${motor.rental_rate ? `
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-1.5">
                                <div class="text-[9px] font-semibold text-blue-800 mb-1">HARGA SEWA</div>
                                <div class="grid grid-cols-3 gap-1 text-[10px]">
                                    <div class="text-center">
                                        <div class="text-gray-500">Harian</div>
                                        <div class="font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-500">Mingguan</div>
                                        <div class="font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.weekly_rate)}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-500">Bulanan</div>
                                        <div class="font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.monthly_rate)}</div>
                                    </div>
                                </div>
                            </div>
                        ` : `
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-1.5 text-center text-[10px]">
                                <i class="bi bi-exclamation-circle text-yellow-600"></i>
                                <div class="text-yellow-800 mt-0.5">Harga sewa belum tersedia</div>
                            </div>
                        `}
                    </div>
                </div>
                
                <!-- Documents Section -->
                ${motor.stnk_photo || motor.registration_doc ? `
                    <div class="mt-2 border-t border-gray-200 pt-2">
                        <div class="text-[10px] font-semibold text-gray-700 mb-1">DOKUMEN KENDARAAN</div>
                        <div class="grid grid-cols-2 gap-1">
                            ${motor.stnk_photo ? `
                                <button onclick="showDocumentPreview('/storage/${motor.stnk_photo}')" 
                                        class="bg-gray-50 border border-gray-200 rounded p-1.5 hover:bg-gray-100 transition text-[10px] text-left">
                                    <i class="bi bi-file-earmark-text text-blue-600 mr-1"></i>
                                    <span class="font-medium">STNK</span>
                                </button>
                            ` : ''}
                            ${motor.registration_doc ? `
                                <button onclick="showDocumentPreview('/storage/${motor.registration_doc}')" 
                                        class="bg-gray-50 border border-gray-200 rounded p-1.5 hover:bg-gray-100 transition text-[10px] text-left">
                                    <i class="bi bi-file-earmark-text text-blue-600 mr-1"></i>
                                    <span class="font-medium">Dokumen Registrasi</span>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                ` : ''}
            `;
            
            // Update booking button
            if (motor.rental_rate) {
                @if($isVerified)
                bookingContainer.innerHTML = `
                    <a href="/penyewa/booking/${motor.id}" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="bi bi-calendar-plus mr-0.5 text-[10px]"></i>Sewa
                    </a>
                `;
                @else
                bookingContainer.innerHTML = `
                    <button class="px-2 py-1 text-xs bg-gray-300 text-gray-700 rounded cursor-not-allowed" disabled>`
                        <i class="bi bi-lock mr-1"></i>Perlu Verifikasi
                    </button>
                `;
                @endif
            }
        })
        .catch(error => {
            console.error('Error fetching motor detail:', error);
            content.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <i class="bi bi-exclamation-triangle text-red-600 text-4xl mb-3"></i>
                    <h6 class="text-red-800 font-semibold mb-2">Error</h6>
                    <p class="text-red-600 mb-4">Gagal memuat detail motor.</p>
                    <button onclick="showMotorDetail(${motorId})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="bi bi-arrow-clockwise mr-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}

// Function to show document preview
function showDocumentPreview(imageUrl) {
    // Create modal for image preview
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-75';
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-[90vh] p-4">
            <button onclick="this.closest('.fixed').remove()" 
                    class="absolute top-6 right-6 text-white hover:text-gray-300 text-2xl z-10">
                <i class="bi bi-x-lg"></i>
            </button>
            <img src="${imageUrl}" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
        </div>
    `;
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    };
    document.body.appendChild(modal);
}
</script>
@endpush
@endsection