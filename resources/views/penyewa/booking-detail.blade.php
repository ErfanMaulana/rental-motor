@extends('layouts.fann')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Booking</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap pemesanan motor</p>
            </div>
            <a href="{{ route('penyewa.bookings') }}" class="flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="bi bi-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-check-circle text-xl mr-3"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-circle text-xl mr-3"></i>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Booking Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Status Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Informasi Booking</h2>
                            <p class="text-sm text-gray-500">Kode: #{{ $booking->booking_code }}</p>
                        </div>
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
                                'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Dikonfirmasi'],
                                'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Sedang Disewa'],
                                'completed' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Selesai'],
                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Dibatalkan']
                            ];
                            $status = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($booking->status)];
                        @endphp
                        <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} text-sm font-medium rounded-full">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <i class="bi bi-calendar3 text-blue-600 text-xl mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Mulai</p>
                                <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="bi bi-calendar-check text-green-600 text-xl mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Selesai</p>
                                <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="bi bi-clock text-purple-600 text-xl mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-500">Durasi</p>
                                <p class="font-medium text-gray-800">
                                    @php
                                        $days = \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) + 1;
                                    @endphp
                                    {{ $days }} hari
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="bi bi-cash-stack text-orange-600 text-xl mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-500">Total Biaya</p>
                                <p class="font-medium text-gray-800">Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Catatan:</p>
                            <p class="text-gray-700">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Payment Status Card -->
                @if($booking->payments && $booking->payments->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="bi bi-credit-card mr-2"></i>Status Pembayaran
                        </h2>
                        
                        @foreach($booking->payments as $payment)
                            <div class="border-l-4 {{ $payment->status === 'paid' ? 'border-green-500' : 'border-yellow-500' }} pl-4 mb-4 last:mb-0">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-medium text-gray-800">
                                        {{ ucfirst($payment->payment_type) }}
                                    </p>
                                    @php
                                        $paymentStatusConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Belum Dibayar'],
                                            'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Lunas'],
                                            'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Gagal']
                                        ];
                                        $paymentStatus = $paymentStatusConfig[$payment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($payment->status)];
                                    @endphp
                                    <span class="px-2 py-1 {{ $paymentStatus['bg'] }} {{ $paymentStatus['text'] }} text-xs font-medium rounded-full">
                                        {{ $paymentStatus['label'] }}
                                    </span>
                                </div>
                                <p class="text-lg font-bold text-gray-800 mb-1">
                                    Rp {{ number_format((float)($payment->amount ?? 0), 0, ',', '.') }}
                                </p>
                                @if($payment->payment_method)
                                    <p class="text-sm text-gray-600">Metode: {{ ucfirst($payment->payment_method) }}</p>
                                @endif
                                @if($payment->payment_date)
                                    <p class="text-sm text-gray-500">
                                        Dibayar: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-between bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <a href="{{ route('penyewa.bookings') }}" class="flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                    
                    @if($booking->status === 'pending')
                        <div class="flex gap-2">
                            <a href="{{ route('penyewa.payment.form', $booking->id) }}" 
                               class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                <i class="bi bi-credit-card mr-2"></i>Bayar Sekarang
                            </a>
                            
                            <form action="{{ route('penyewa.booking.cancel', $booking->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center px-4 py-2 bg-white border-2 border-red-500 text-red-600 rounded-lg hover:bg-red-50 transition">
                                    <i class="bi bi-x-circle mr-2"></i>Batalkan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Motor & Owner Info -->
            <div class="space-y-6">
                <!-- Motor Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    @if($booking->motor->photo)
                        <img src="{{ asset('storage/' . $booking->motor->photo) }}" 
                             alt="{{ $booking->motor->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="bi bi-scooter text-6xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $booking->motor->model }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="bi bi-tag mr-2"></i>
                                <span>{{ $booking->motor->brand }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="bi bi-gear mr-2"></i>
                                <span>{{ $booking->motor->type_cc }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="bi bi-palette mr-2"></i>
                                <span>{{ $booking->motor->color }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="bi bi-card-text mr-2"></i>
                                <span>{{ $booking->motor->plate_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="bi bi-person-circle mr-2"></i>Pemilik Motor
                    </h3>
                    <div class="space-y-2">
                        <div class="flex items-start">
                            <i class="bi bi-person text-gray-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-500">Nama</p>
                                <p class="font-medium text-gray-800">{{ $booking->motor->owner->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="bi bi-envelope text-gray-400 mr-2 mt-1"></i>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-800">{{ $booking->motor->owner->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($booking->motor->owner->phone ?? false)
                            <div class="flex items-start">
                                <i class="bi bi-telephone text-gray-400 mr-2 mt-1"></i>
                                <div>
                                    <p class="text-sm text-gray-500">No. Telepon</p>
                                    <p class="font-medium text-gray-800">{{ $booking->motor->owner->phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rental Rates Card -->
                @if($booking->motor && $booking->motor->rentalRates && is_object($booking->motor->rentalRates) && $booking->motor->rentalRates->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="bi bi-currency-dollar mr-2"></i>Tarif Sewa
                        </h3>
                        <div class="space-y-2">
                            @foreach($booking->motor->rentalRates as $rate)
                                @if(is_object($rate))
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                        <span class="text-sm text-gray-600">{{ ucfirst($rate->type ?? 'N/A') }}</span>
                                        <span class="font-semibold text-gray-800">
                                            Rp {{ number_format((float)($rate->price ?? 0), 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                            <div class="flex justify-between items-center py-2 bg-blue-50 px-3 rounded-lg mt-2">
                                <span class="text-sm font-medium text-blue-900">Durasi</span>
                                <span class="font-bold text-blue-900">{{ $days ?? 0 }} hari</span>
                            </div>
                            <div class="flex justify-between items-center py-2 bg-green-50 px-3 rounded-lg">
                                <span class="text-sm font-bold text-green-900">Total</span>
                                <span class="font-bold text-green-900 text-lg">
                                    Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rating Section for Completed Bookings -->
        @if($booking->status === 'completed')
            @php
                $existingRating = App\Models\Rating::where('user_id', auth()->id())
                    ->where('booking_id', $booking->id)
                    ->first();
            @endphp
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-star mr-2 text-yellow-500"></i>Rating & Ulasan Motor
                </h2>
                
                @if($existingRating)
                    <!-- Display existing rating -->
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <h6 class="font-semibold text-green-800 mb-3 flex items-center">
                            <i class="bi bi-check-circle mr-2"></i>Rating Anda
                        </h6>
                        <div class="flex items-center mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $existingRating->rating ? '-fill' : '' }} text-yellow-400 text-xl mr-1"></i>
                            @endfor
                            <span class="ml-2 font-semibold text-gray-700">{{ $existingRating->rating }}/5</span>
                        </div>
                        @if($existingRating->review)
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-700 mb-1">Ulasan:</p>
                                <p class="text-gray-700">{{ $existingRating->review }}</p>
                            </div>
                        @endif
                        <p class="text-sm text-gray-600">
                            Diberikan pada {{ $existingRating->created_at->format('d M Y H:i') }}
                        </p>
                        
                        @if($existingRating->canEdit())
                            <div class="flex gap-2 mt-4">
                                <button onclick="editRating({{ $existingRating->id }}, {{ $existingRating->rating }}, '{{ $existingRating->review }}')" 
                                        class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="bi bi-pencil mr-1"></i>Edit Rating
                                </button>
                                <button onclick="deleteRating({{ $existingRating->id }})" 
                                        class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition flex items-center">
                                    <i class="bi bi-trash mr-1"></i>Hapus Rating
                                </button>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 mt-3 flex items-start">
                                <i class="bi bi-info-circle mr-2 mt-0.5"></i>
                                <span>Rating hanya dapat diedit dalam 24 jam pertama</span>
                            </p>
                        @endif
                    </div>
                @else
                    <!-- Rating form -->
                    <form id="ratingForm" onsubmit="submitRating(event)">
                        @csrf
                        <input type="hidden" name="motor_id" value="{{ $booking->motor_id }}">
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating Motor</label>
                            <div class="flex items-center space-x-2 mb-2" id="ratingStars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star rating-star cursor-pointer text-4xl text-gray-300 hover:text-yellow-400 transition" 
                                       data-rating="{{ $i }}" 
                                       onclick="setRating({{ $i }})"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" required>
                            <p class="text-sm text-gray-500">Klik bintang untuk memberikan rating</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="review" class="block text-sm font-medium text-gray-700 mb-2">
                                Ulasan (Opsional)
                            </label>
                            <textarea name="review" 
                                      id="review" 
                                      rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Bagikan pengalaman Anda dengan motor ini..."></textarea>
                        </div>
                        
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="bi bi-star-fill mr-2"></i>Kirim Rating
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let selectedRating = 0;

function setRating(rating) {
    selectedRating = rating;
    document.getElementById('ratingValue').value = rating;
    
    // Update star display
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('bi-star', 'text-gray-300');
            star.classList.add('bi-star-fill', 'text-yellow-400');
        } else {
            star.classList.remove('bi-star-fill', 'text-yellow-400');
            star.classList.add('bi-star', 'text-gray-300');
        }
    });
}

function submitRating(event) {
    event.preventDefault();
    
    if (selectedRating === 0) {
        alert('Silakan pilih rating terlebih dahulu');
        return;
    }
    
    const formData = new FormData(event.target);
    
    fetch('{{ route("penyewa.rating.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi error saat mengirim rating');
    });
}

function editRating(ratingId, currentRating, currentReview) {
    const isConfirm = confirm('Apakah Anda ingin mengedit rating ini?');
    if (isConfirm) {
        const newRating = prompt('Rating baru (1-5):', currentRating);
        const newReview = prompt('Ulasan baru:', currentReview);
        
        if (newRating && newRating >= 1 && newRating <= 5) {
            const formData = new FormData();
            formData.append('rating', newRating);
            formData.append('review', newReview || '');
            formData.append('_method', 'PUT');
            
            fetch(`/penyewa/ratings/${ratingId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            });
        }
    }
}

function deleteRating(ratingId) {
    if (confirm('Apakah Anda yakin ingin menghapus rating ini?')) {
        fetch(`/penyewa/ratings/${ratingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                location.reload();
            } else if (data.error) {
                alert(data.error);
            }
        });
    }
}
</script>
@endpush
@endsection
