@extends('layouts.fann')

@section('title', 'Detail Motor')

@push('styles')
<style>
    /* Reset all potential Bootstrap conflicts */
    * {
        margin: revert;
        padding: revert;
    }
    
    /* Force Tailwind styles */
    input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Ensure form elements are styled */
    form input, form button, form label, form span {
        font-family: inherit;
        line-height: inherit;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-motorcycle mr-3"></i>Detail Motor
        </h1>
        <p class="text-sm text-gray-500 mt-1">Informasi lengkap motor untuk verifikasi</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Motor Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-info-circle mr-2"></i>Informasi Motor
                    </h5>
                    <div>
                        @if($motor->status === 'pending_verification')
                            <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">Menunggu Verifikasi</span>
                        @elseif($motor->status === 'available')
                            <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Tersedia</span>
                        @elseif($motor->status === 'rented')
                            <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">Disewa</span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">{{ ucfirst($motor->status) }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($motor->photo)
                                <img src="{{ Storage::url($motor->photo) }}" 
                                     alt="{{ $motor->brand }} {{ $motor->model }}"
                                     class="w-full h-64 object-cover rounded-lg shadow-sm">
                            @else
                                <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="bi bi-motorcycle text-gray-400 text-6xl"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $motor->brand }} {{ $motor->model }}</h3>
                            
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">{{ $motor->type_cc }}</span>
                                @if($motor->status === 'available')
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Tersedia</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Tidak Tersedia</span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h6 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="bi bi-calendar mr-2"></i>Tahun
                                    </h6>
                                    <p class="text-sm text-gray-900">{{ $motor->year }}</p>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="bi bi-palette mr-2"></i>Warna
                                    </h6>
                                    <p class="text-sm text-gray-900">{{ $motor->color }}</p>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="bi bi-card-text mr-2"></i>Plat Nomor
                                    </h6>
                                    <p class="text-sm text-gray-900">{{ $motor->plate_number }}</p>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="bi bi-person mr-2"></i>Pemilik
                                    </h6>
                                    <p class="text-sm text-gray-900">{{ $motor->owner->name }}</p>
                                    <small class="text-xs text-gray-500">{{ $motor->owner->email }}</small>
                                </div>
                            </div>
                            
                            @if($motor->description)
                                <div class="mt-4">
                                    <h6 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                        <i class="bi bi-chat-text mr-2"></i>Deskripsi
                                    </h6>
                                    <p class="text-sm text-gray-600">{{ $motor->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($motor->status === 'pending_verification')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-check-circle mr-2"></i>Verifikasi Motor
                        </h5>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <i class="bi bi-info-circle text-blue-600 text-xl mr-3 mt-0.5"></i>
                                <div>
                                    <h6 class="font-semibold text-blue-900 mb-1">Perhatian:</h6>
                                    <p class="text-sm text-blue-800">Setelah motor diverifikasi dan harga ditetapkan, motor akan tersedia untuk disewa oleh penyewa.</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.motor.verify', $motor->id) }}" method="POST" class="space-y-6" style="display: block;">
                            @csrf
                            @method('PATCH')
                            
                            <div style="margin-bottom: 1.5rem;">
                                <label for="daily_rate" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                                    <i class="bi bi-currency-dollar" style="margin-right: 0.25rem;"></i>Tarif Harian *
                                </label>
                                <div style="display: flex; align-items: center;">
                                    <span style="display: inline-flex; align-items: center; padding: 0.625rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-right: 0; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; font-size: 0.875rem; color: #374151;">Rp</span>
                                    <input type="number" 
                                           style="flex: 1; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; font-size: 0.875rem; outline: none;"
                                           id="daily_rate" 
                                           name="daily_rate" 
                                           min="10000" 
                                           max="1000000"
                                           step="1000"
                                           placeholder="450000"
                                           required
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                                           onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
                                </div>
                                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">Minimal Rp 10.000 - Maksimal Rp 1.000.000</p>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Tarif Mingguan</label>
                                <div style="display: flex; align-items: center;">
                                    <span style="display: inline-flex; align-items: center; padding: 0.625rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #6b7280;">Otomatis dengan diskon</span>
                                </div>
                                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">Auto: diskon 10% dari 7x tarif harian</p>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Tarif Bulanan</label>
                                <div style="display: flex; align-items: center;">
                                    <span style="display: inline-flex; align-items: center; padding: 0.625rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #6b7280;">Otomatis dengan diskon</span>
                                </div>
                                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">Auto: diskon 20% dari 30x tarif harian</p>
                            </div>

                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                                <h6 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: flex; align-items: center;">
                                    <i class="bi bi-lightbulb" style="margin-right: 0.5rem; color: #eab308;"></i>Tips Penetapan Harga
                                </h6>
                                <ul style="font-size: 0.75rem; color: #6b7280; list-style: none; padding: 0; margin: 0;">
                                    <li style="margin-bottom: 0.25rem;">• Motor 100cc-125cc: Rp 50.000 - 80.000/hari</li>
                                    <li style="margin-bottom: 0.25rem;">• Motor 150cc: Rp 80.000 - 120.000/hari</li>
                                    <li style="margin-bottom: 0.25rem;">• Motor 250cc+: Rp 120.000 - 200.000/hari</li>
                                    <li style="margin-bottom: 0.25rem;">• Motor Premium/Sport: Rp 300.000 - 1.000.000/hari</li>
                                    <li style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #d1d5db;">Pertimbangkan kondisi, umur, dan brand motor</li>
                                </ul>
                            </div>

                            <p style="font-size: 0.75rem; color: #6b7280; font-style: italic; margin-bottom: 1.5rem;"><strong>Catatan:</strong> Tarif mingguan dan bulanan akan otomatis dihitung berdasarkan tarif harian (diskon 10% untuk mingguan, diskon 20% untuk bulanan).</p>

                            <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <a href="{{ route('admin.motors') }}" style="display: inline-flex; align-items: center; padding: 0.625rem 1.5rem; background-color: #f3f4f6; color: #374151; font-weight: 500; border-radius: 0.5rem; text-decoration: none; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='#f3f4f6'">
                                    <i class="bi bi-arrow-left" style="margin-right: 0.5rem;"></i>Batal
                                </a>
                                <button type="submit" style="display: inline-flex; align-items: center; padding: 0.625rem 1.5rem; background-color: #16a34a; color: white; font-weight: 500; border-radius: 0.5rem; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                                    <i class="bi bi-check-circle" style="margin-right: 0.5rem;"></i>Verifikasi & Set Harga
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="flex justify-start">
                    <a href="{{ route('admin.motors') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition flex items-center">
                        <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar Motor
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            @if($motor->rentalRate)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-currency-dollar mr-2"></i>Harga Sewa
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="text-center pb-4 border-b border-gray-200">
                                <h6 class="text-sm font-medium text-gray-600 mb-2">Harian</h6>
                                <h4 class="text-2xl font-bold text-blue-600">Rp {{ number_format((float)$motor->rentalRate->daily_rate, 0, ',', '.') }}</h4>
                                <small class="text-xs text-gray-500">per hari</small>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <h6 class="text-sm font-medium text-gray-600 mb-2">Mingguan</h6>
                                    <h5 class="text-lg font-bold text-blue-500">Rp {{ number_format((float)$motor->rentalRate->daily_rate * 7 * 0.9, 0, ',', '.') }}</h5>
                                    <small class="text-xs text-gray-500">per minggu</small>
                                    <br><small class="text-xs text-green-600 font-medium">Diskon 10%</small>
                                </div>
                                <div class="text-center">
                                    <h6 class="text-sm font-medium text-gray-600 mb-2">Bulanan</h6>
                                    <h5 class="text-lg font-bold text-blue-500">Rp {{ number_format((float)$motor->rentalRate->daily_rate * 30 * 0.8, 0, ',', '.') }}</h5>
                                    <small class="text-xs text-gray-500">per bulan</small>
                                    <br><small class="text-xs text-green-600 font-medium">Diskon 20%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-bar-chart mr-2"></i>Statistik Motor
                    </h6>
                </div>
                <div class="p-6">
                    @php
                        $totalBookings = $motor->bookings()->count();
                        $totalEarnings = $motor->bookings()->where('status', 'completed')->sum('price');
                    @endphp
                    
                    <div class="space-y-4">
                        <div class="text-center pb-4 border-b border-gray-200">
                            <h4 class="text-3xl font-bold text-blue-600">{{ $totalBookings }}</h4>
                            <small class="text-sm text-gray-600">Total Booking</small>
                        </div>
                        <div class="text-center">
                            <h4 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h4>
                            <small class="text-sm text-gray-600">Total Earnings</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="bi bi-person mr-2"></i>Informasi Pemilik
                    </h6>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bi bi-person-fill text-blue-600 text-2xl"></i>
                        </div>
                        <h6 class="font-semibold text-gray-900 mb-1">{{ $motor->owner->name }}</h6>
                        <p class="text-sm text-gray-600 mb-2">{{ $motor->owner->email }}</p>
                        @if($motor->owner->phone)
                            <p class="text-sm text-gray-600 mb-2 flex items-center justify-center">
                                <i class="bi bi-phone mr-1"></i>{{ $motor->owner->phone }}
                            </p>
                        @endif
                        <small class="text-xs text-gray-500">
                            Bergabung: {{ $motor->owner->created_at->format('d M Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection