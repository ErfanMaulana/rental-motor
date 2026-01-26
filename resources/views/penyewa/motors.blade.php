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
             class="inline-block w-full max-w-5xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-blue-600 text-white">
                <h3 class="text-lg font-medium flex items-center">
                    <i class="bi bi-scooter mr-2"></i>Detail Motor
                </h3>
                <button @click="open = false" class="text-white hover:text-gray-200 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div id="motorDetailContent" class="px-6 py-4">
                <div class="flex justify-center py-12">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
                        <p class="mt-4 text-gray-600">Memuat detail motor...</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200 space-x-2">
                <button @click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="bi bi-x-lg mr-1"></i>Tutup
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
    const content = document.getElementById('motorDetailContent');
    const bookingContainer = document.getElementById('bookingButtonContainer');
    
    // Reset content with Tailwind loading
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
            const motor = data.motor;
            
            // Build motor detail HTML with Tailwind
            let rentalRateHtml = '';
            if (motor.rental_rate) {
                rentalRateHtml = `
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h6 class="text-sm font-medium text-gray-700 mb-2">Tarif Sewa Harian</h6>
                        <div class="text-2xl font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                        <small class="text-gray-500">per hari</small>
                    </div>
                `;
            }
            
            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        ${motor.photo ? 
                            `<img src="/storage/${motor.photo}" class="w-full rounded-lg shadow-sm" alt="${motor.brand} ${motor.model}">` :
                            `<div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-scooter text-gray-300 text-6xl"></i>
                             </div>`
                        }
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">${motor.brand} ${motor.model}</h3>
                        <div class="flex gap-2 mb-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">${motor.type_cc}</span>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="bi bi-check-circle mr-1"></i>Tersedia
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <strong class="text-gray-700">Tahun:</strong><br>
                                <span class="text-gray-600">${motor.year}</span>
                            </div>
                            <div>
                                <strong class="text-gray-700">Warna:</strong><br>
                                <span class="text-gray-600">${motor.color}</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <strong class="text-gray-700">Plat Nomor:</strong><br>
                                <span class="text-gray-600">${motor.plate_number}</span>
                            </div>
                            <div>
                                <strong class="text-gray-700">Pemilik:</strong><br>
                                <span class="text-gray-600">${motor.owner.name}</span>
                            </div>
                        </div>
                        
                        ${motor.description ? `
                            <div class="mb-4">
                                <strong class="text-gray-700">Deskripsi:</strong><br>
                                <p class="text-gray-600 mt-1">${motor.description}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                ${rentalRateHtml ? `
                    <div class="mt-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-3">Harga Sewa</h5>
                        ${rentalRateHtml}
                    </div>
                ` : `
                    <div class="mt-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <i class="bi bi-exclamation-circle text-yellow-600 text-xl"></i>
                            <p class="text-yellow-800 mt-2">Harga sewa belum tersedia. Silakan hubungi pemilik motor.</p>
                        </div>
                    </div>
                `}
                
                <!-- Rating Section -->
                <div class="mt-6">
                    <h5 class="text-lg font-semibold text-gray-900 mb-3">Rating & Ulasan</h5>
                    <div id="ratingsSection">
                        <div class="text-center text-gray-500 py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"></div>
                            <p class="mt-2">Loading ratings...</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Load ratings for this motor
            loadMotorRatings(motor.id);
            
            // Update booking button
            if (motor.rental_rate) {
                @if(!$isVerified)
                bookingContainer.innerHTML = `
                    <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg cursor-not-allowed" disabled>
                        <i class="bi bi-lock mr-1"></i>Perlu Verifikasi
                    </button>
                `;
                @else
                bookingContainer.innerHTML = `
                    <a href="/penyewa/booking/${motor.id}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-block">
                        <i class="bi bi-calendar-plus mr-1"></i>Sewa Sekarang
                    </a>
                `;
                @endif
            }
        })
        .catch(error => {
            console.error('Error fetching motor detail:', error);
            content.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center text-red-800 mb-3">
                        <i class="bi bi-exclamation-triangle text-xl mr-2"></i>
                        <h6 class="font-semibold">Error</h6>
                    </div>
                    <p class="text-red-700 mb-4">Gagal memuat detail motor.</p>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" onclick="showMotorDetail(${motorId})">
                        <i class="bi bi-arrow-clockwise mr-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}

// Function to load motor ratings
function loadMotorRatings(motorId) {
    fetch(`/penyewa/ratings/${motorId}`)
        .then(response => response.json())
        .then(data => {
            const ratingsSection = document.getElementById('ratingsSection');
            
            if (data.ratings.data.length === 0) {
                ratingsSection.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-star me-1"></i>
                        Belum ada rating untuk motor ini
                    </div>
                `;
                return;
            }
            
            // Display average rating
            let averageDisplay = '';
            if (data.average_rating > 0) {
                averageDisplay = `
                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-light rounded">
                        <div>
                            <h6 class="mb-1">Rating Rata-rata</h6>
                            <div class="d-flex align-items-center">
                                ${generateStarRating(data.average_rating)}
                                <span class="ms-2 fw-bold">${data.average_rating.toFixed(1)}</span>
                                <span class="ms-1 text-muted">(${data.total_ratings} ulasan)</span>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Display individual ratings
            let ratingsHtml = data.ratings.data.map(rating => `
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <strong class="me-2">${rating.user.name}</strong>
                                ${generateStarRating(rating.rating)}
                                <small class="ms-2 text-muted">${new Date(rating.created_at).toLocaleDateString('id-ID')}</small>
                            </div>
                            ${rating.review ? `<p class="mb-0 text-muted">${rating.review}</p>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
            
            ratingsSection.innerHTML = averageDisplay + ratingsHtml;
        })
        .catch(error => {
            console.error('Error loading ratings:', error);
            document.getElementById('ratingsSection').innerHTML = `
                <div class="text-center text-danger py-3">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Gagal memuat rating
                </div>
            `;
        });
}

// Function to generate star rating display
function generateStarRating(rating) {
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHtml += '<i class="bi bi-star-fill text-warning"></i>';
        } else if (i - 0.5 <= rating) {
            starsHtml += '<i class="bi bi-star-half text-warning"></i>';
        } else {
            starsHtml += '<i class="bi bi-star text-muted"></i>';
        }
    }
    return starsHtml;
}
                `}
            `;
            
            // Add booking button if rates available
            if (motor.rental_rate) {
                bookingContainer.innerHTML = `
                    <a href="/penyewa/booking/${motor.id}" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-1"></i>Sewa Sekarang
                    </a>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching motor detail:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Error</h6>
                    <p class="mt-2 text-danger">Gagal memuat detail motor.</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="showMotorDetail(${motorId})">
                        <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}
</script>
@endpush
@endsection