@extends('layouts.fann')

@section('title', 'Form Pemesanan Motor')

@section('content')
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
        <i class="bi bi-calendar-plus mr-3 text-blue-600"></i>
        Form Pemesanan Motor
    </h1>
    <p class="text-gray-600 mt-1">Lengkapi informasi pemesanan motor Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900">Informasi Pemesanan</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('penyewa.booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="motor_id" value="{{ $motor->id }}">
                    <input type="hidden" name="start_date" id="hidden_start_date">
                    <input type="hidden" name="end_date" id="hidden_end_date">
                    <input type="hidden" name="package_type" id="hidden_package_type">
                    
                    <!-- Step 1: Pilih Paket -->
                    <div class="mb-6" id="step-package">
                        <h6 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">1</span>
                            Pilih Paket Sewa
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Paket Harian -->
                            <div class="package-card border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-blue-500 transition" data-package="daily">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="bi bi-calendar-day text-blue-600 text-5xl"></i>
                                    </div>
                                    <h6 class="text-lg font-bold text-gray-900">Paket Harian</h6>
                                    <p class="text-sm text-gray-500 mb-3">Sewa per hari (1-6 hari)</p>
                                    @if($motor->rentalRate)
                                        <div class="text-2xl font-bold text-blue-600 mb-2">
                                            Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span>
                                        </div>
                                    @endif
                                    <div class="mt-3 space-y-1 text-sm">
                                        <p class="text-green-600">✓ Fleksibel</p>
                                        <p class="text-green-600">✓ Cocok untuk jarak dekat</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Paket Mingguan -->
                            <div class="package-card border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-green-500 transition" data-package="weekly">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="bi bi-calendar-week text-green-600 text-5xl"></i>
                                    </div>
                                    <h6 class="text-lg font-bold text-gray-900">Paket Mingguan</h6>
                                    <p class="text-sm text-gray-500 mb-3">Sewa per minggu (1-4 minggu)</p>
                                    @if($motor->rentalRate)
                                        <div class="text-2xl font-bold text-green-600 mb-2">
                                            Rp {{ number_format($motor->rentalRate->weekly_rate ?? ($motor->rentalRate->daily_rate * 7 * 0.9), 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/minggu</span>
                                        </div>
                                        <small class="text-green-600 font-semibold">Hemat 10%!</small>
                                    @endif
                                    <div class="mt-3 space-y-1 text-sm">
                                        <p class="text-green-600">✓ Lebih hemat</p>
                                        <p class="text-green-600">✓ Cocok untuk liburan</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Paket Bulanan -->
                            <div class="package-card border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-yellow-500 transition" data-package="monthly">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="bi bi-calendar-month text-yellow-600 text-5xl"></i>
                                    </div>
                                    <h6 class="text-lg font-bold text-gray-900">Paket Bulanan</h6>
                                    <p class="text-sm text-gray-500 mb-3">Sewa sebulan penuh</p>
                                    @if($motor->rentalRate)
                                        <div class="text-2xl font-bold text-yellow-600 mb-2">
                                            Rp {{ number_format($motor->rentalRate->monthly_rate ?? ($motor->rentalRate->daily_rate * 30 * 0.8), 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/bulan</span>
                                        </div>
                                        <small class="text-yellow-600 font-semibold">Hemat 20%!</small>
                                    @endif
                                    <div class="mt-3 space-y-1 text-sm">
                                        <p class="text-green-600">✓ Paling hemat</p>
                                        <p class="text-green-600">✓ Cocok untuk jangka panjang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Pilih Tanggal -->
                    <div class="mb-6 hidden" id="step-date">
                        <h6 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">2</span>
                            Pilih Tanggal & Durasi
                        </h6>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       id="start_date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (Otomatis)</label>
                                <input type="date" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" 
                                       id="end_date_display" 
                                       readonly>
                            </div>
                        </div>
                        
                        <div id="duration-selector" class="mb-4"></div>
                        
                        <div id="availability-status"></div>
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" 
                                    onclick="goToStep(1)">
                                <i class="bi bi-arrow-left mr-2"></i>Kembali
                            </button>
                            <button type="button" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition hidden" 
                                    id="btn-next" 
                                    onclick="goToStep(3)">
                                Lanjut ke Ringkasan<i class="bi bi-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Ringkasan & Submit -->
                    <div class="mb-6 hidden" id="step-summary">
                        <h6 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold mr-3">3</span>
                            Ringkasan Pemesanan
                        </h6>
                        
                        <div id="summary-content" class="bg-gray-50 rounded-lg p-4 mb-4"></div>
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" 
                                    onclick="goToStep(2)">
                                <i class="bi bi-arrow-left mr-2"></i>Kembali
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition" 
                                    id="btn-submit">
                                <i class="bi bi-check-circle mr-2"></i>Konfirmasi Pemesanan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Motor Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900">Motor Yang Dipesan</h5>
            </div>
            <div class="p-6">
                @if($motor->photo)
                    <img src="{{ Storage::url($motor->photo) }}" 
                         class="w-full h-48 object-cover rounded-lg mb-4" 
                         alt="{{ $motor->brand }} {{ $motor->model }}">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center rounded-lg mb-4">
                        <i class="bi bi-scooter text-gray-400 text-6xl"></i>
                    </div>
                @endif
                
                <h5 class="text-xl font-bold text-gray-900 mb-2">{{ $motor->brand }} {{ $motor->model }}</h5>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-calendar3 w-5"></i>
                        <span class="ml-2">Tahun: {{ $motor->year }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-gear w-5"></i>
                        <span class="ml-2">{{ $motor->type_cc }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-credit-card w-5"></i>
                        <span class="ml-2">{{ $motor->plate_number }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-person w-5"></i>
                        <span class="ml-2">Pemilik: {{ $motor->owner->name }}</span>
                    </div>
                </div>
                
                @if($motor->rentalRate)
                    <div class="border-t border-gray-200 pt-4">
                        <h6 class="text-sm font-semibold text-gray-700 mb-2">Tarif Sewa:</h6>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harian:</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mingguan:</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($motor->rentalRate->weekly_rate ?? ($motor->rentalRate->daily_rate * 7 * 0.9), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bulanan:</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($motor->rentalRate->monthly_rate ?? ($motor->rentalRate->daily_rate * 30 * 0.8), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedPackage = null;
let selectedDuration = 1;
let currentStep = 1;
const motorId = {{ $motor->id }};

@if($motor->rentalRate)
const rates = {
    daily: {{ $motor->rentalRate->daily_rate }},
    weekly: {{ $motor->rentalRate->weekly_rate ?? ($motor->rentalRate->daily_rate * 7 * 0.9) }},
    monthly: {{ $motor->rentalRate->monthly_rate ?? ($motor->rentalRate->daily_rate * 30 * 0.8) }}
};
@endif

document.addEventListener('DOMContentLoaded', function() {
    // Package selection
    document.querySelectorAll('.package-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.package-card').forEach(c => {
                c.classList.remove('border-blue-600', 'bg-blue-50');
                c.classList.add('border-gray-200');
            });
            
            this.classList.remove('border-gray-200');
            this.classList.add('border-blue-600', 'bg-blue-50');
            
            selectedPackage = this.dataset.package;
            document.getElementById('hidden_package_type').value = selectedPackage;
            
            // Generate duration selector
            generateDurationSelector();
            
            // Show step 2
            setTimeout(() => goToStep(2), 300);
        });
    });
    
    // Date change
    document.getElementById('start_date').addEventListener('change', function() {
        if (!this.value || !selectedPackage) return;
        
        const startDate = new Date(this.value);
        let endDate = new Date(startDate);
        
        if (selectedPackage === 'daily') {
            endDate.setDate(startDate.getDate() + selectedDuration - 1);
        } else if (selectedPackage === 'weekly') {
            endDate.setDate(startDate.getDate() + (selectedDuration * 7) - 1);
        } else if (selectedPackage === 'monthly') {
            endDate.setMonth(startDate.getMonth() + selectedDuration);
            endDate.setDate(endDate.getDate() - 1);
        }
        
        document.getElementById('end_date_display').value = endDate.toISOString().split('T')[0];
        document.getElementById('hidden_start_date').value = this.value;
        document.getElementById('hidden_end_date').value = endDate.toISOString().split('T')[0];
        
        document.getElementById('btn-next').classList.remove('hidden');
        
        // Check availability
        checkAvailability();
        updateSummary();
    });
    
    // Form submit
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('btn-submit');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Memproses...';
        submitBtn.disabled = true;
    });
});

function generateDurationSelector() {
    const selector = document.getElementById('duration-selector');
    let html = '<label class="block text-sm font-medium text-gray-700 mb-2">Durasi</label>';
    html += '<div class="flex gap-2 flex-wrap">';
    
    const maxDuration = selectedPackage === 'daily' ? 6 : (selectedPackage === 'weekly' ? 4 : 1);
    const unit = selectedPackage === 'daily' ? 'hari' : (selectedPackage === 'weekly' ? 'minggu' : 'bulan');
    
    for (let i = 1; i <= maxDuration; i++) {
        html += `<button type="button" class="duration-btn px-4 py-2 border-2 border-gray-300 rounded-lg hover:border-blue-500 transition" data-duration="${i}">
            ${i} ${unit}
        </button>`;
    }
    
    html += '</div>';
    selector.innerHTML = html;
    
    // Add event listeners
    document.querySelectorAll('.duration-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.duration-btn').forEach(b => {
                b.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
                b.classList.add('border-gray-300');
            });
            
            this.classList.remove('border-gray-300');
            this.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');
            
            selectedDuration = parseInt(this.dataset.duration);
            
            // Update end date if start date is selected
            const startDateInput = document.getElementById('start_date');
            if (startDateInput.value) {
                startDateInput.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Auto select first duration
    document.querySelector('.duration-btn').click();
}

function goToStep(step) {
    // Hide all steps
    document.getElementById('step-package').classList.add('hidden');
    document.getElementById('step-date').classList.add('hidden');
    document.getElementById('step-summary').classList.add('hidden');
    
    // Show selected step
    if (step === 1) {
        document.getElementById('step-package').classList.remove('hidden');
    } else if (step === 2) {
        document.getElementById('step-date').classList.remove('hidden');
    } else if (step === 3) {
        document.getElementById('step-summary').classList.remove('hidden');
        updateSummary();
    }
    
    currentStep = step;
}

function checkAvailability() {
    const startDate = document.getElementById('hidden_start_date').value;
    const endDate = document.getElementById('hidden_end_date').value;
    
    if (!startDate || !endDate) return;
    
    fetch('/penyewa/booking/check-availability', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            motor_id: motorId,
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('availability-status');
        const submitBtn = document.getElementById('btn-submit');
        
        if (data.available) {
            statusDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="bi bi-check-circle text-green-600 text-xl mr-2"></i>
                        <div>
                            <strong class="text-green-800">Motor Tersedia!</strong>
                            <p class="text-green-700 text-sm mt-1">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
            submitBtn.disabled = false;
        } else {
            statusDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="bi bi-x-circle text-red-600 text-xl mr-2"></i>
                        <div>
                            <strong class="text-red-800">Motor Tidak Tersedia!</strong>
                            <p class="text-red-700 text-sm mt-1">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
            submitBtn.disabled = true;
        }
    });
}

function updateSummary() {
    const startDate = document.getElementById('hidden_start_date').value;
    const endDate = document.getElementById('hidden_end_date').value;
    
    if (!startDate || !endDate || !selectedPackage) return;
    
    let price = 0;
    let packageName = '';
    
    if (selectedPackage === 'daily') {
        price = rates.daily * selectedDuration;
        packageName = `Paket Harian (${selectedDuration} hari)`;
    } else if (selectedPackage === 'weekly') {
        price = rates.weekly * selectedDuration;
        packageName = `Paket Mingguan (${selectedDuration} minggu)`;
    } else if (selectedPackage === 'monthly') {
        price = rates.monthly * selectedDuration;
        packageName = `Paket Bulanan (${selectedDuration} bulan)`;
    }
    
    const summaryDiv = document.getElementById('summary-content');
    summaryDiv.innerHTML = `
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Paket:</span>
                <span class="font-semibold text-gray-900">${packageName}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Tanggal:</span>
                <span class="font-semibold text-gray-900">${new Date(startDate).toLocaleDateString('id-ID')} - ${new Date(endDate).toLocaleDateString('id-ID')}</span>
            </div>
            <div class="border-t border-gray-300 pt-3 flex justify-between">
                <span class="text-lg font-bold text-gray-900">Total Biaya:</span>
                <span class="text-2xl font-bold text-blue-600">Rp ${price.toLocaleString('id-ID')}</span>
            </div>
        </div>
    `;
}
</script>
@endpush

@endsection
