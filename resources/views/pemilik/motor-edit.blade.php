@extends('layouts.fann')

@section('title', 'Edit Motor - ' . $motor->brand . ' ' . $motor->model)

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Motor</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi motor {{ $motor->brand }} {{ $motor->model }}</p>
        </div>
        <a href="{{ route('pemilik.motors') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-flex items-center">
            <i class="bi bi-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Form Edit Motor -->
<form id="editMotorForm" action="{{ route('pemilik.motor.update', $motor->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Informasi Motor -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-blue-600 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-info-circle mr-2"></i>Informasi Motor
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Merek Motor -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Merek Motor</label>
                            <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('brand') border-red-500 @enderror" 
                                   name="brand" value="{{ old('brand', $motor->brand) }}" required>
                            @error('brand')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Model Motor -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Model Motor</label>
                            <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('model') border-red-500 @enderror" 
                                   name="model" value="{{ old('model', $motor->model) }}" required>
                            @error('model')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                            <input type="number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror" 
                                   name="year" value="{{ old('year', $motor->year) }}" min="2000" max="2026" required>
                            @error('year')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CC -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">CC</label>
                            <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_cc') border-red-500 @enderror" name="type_cc" required>
                                <option value="">Pilih CC</option>
                                <option value="100cc" {{ old('type_cc', $motor->type_cc) == '100cc' ? 'selected' : '' }}>100cc</option>
                                <option value="110cc" {{ old('type_cc', $motor->type_cc) == '110cc' ? 'selected' : '' }}>110cc</option>
                                <option value="125cc" {{ old('type_cc', $motor->type_cc) == '125cc' ? 'selected' : '' }}>125cc</option>
                                <option value="150cc" {{ old('type_cc', $motor->type_cc) == '150cc' ? 'selected' : '' }}>150cc</option>
                                <option value="160cc" {{ old('type_cc', $motor->type_cc) == '160cc' ? 'selected' : '' }}>160cc</option>
                                <option value="250cc" {{ old('type_cc', $motor->type_cc) == '250cc' ? 'selected' : '' }}>250cc</option>
                                <option value="400cc" {{ old('type_cc', $motor->type_cc) == '400cc' ? 'selected' : '' }}>400cc</option>
                                <option value="500cc" {{ old('type_cc', $motor->type_cc) == '500cc' ? 'selected' : '' }}>500cc</option>
                                <option value="600cc" {{ old('type_cc', $motor->type_cc) == '600cc' ? 'selected' : '' }}>600cc</option>
                            </select>
                            @error('type_cc')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Plat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Plat</label>
                            <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('plate_number') border-red-500 @enderror" 
                                   name="plate_number" value="{{ old('plate_number', $motor->plate_number) }}" 
                                   placeholder="Contoh: B 1234 ABC" required>
                            @error('plate_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warna -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
                            <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('color') border-red-500 @enderror" 
                                   name="color" value="{{ old('color', $motor->color) }}" 
                                   placeholder="Contoh: Merah, Hitam, Putih" required>
                            @error('color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                  name="description" rows="4" 
                                  placeholder="Deskripsikan kondisi dan fitur motor...">{{ old('description', $motor->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Maintenance -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-yellow-500 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-tools mr-2"></i>Status Maintenance
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                        <div class="flex-grow-1">
                            <h6 class="font-semibold text-gray-900 mb-2">Aktifkan Mode Maintenance</h6>
                            <p class="text-sm text-gray-600 mb-0">
                                Motor yang sedang maintenance tidak akan tersedia untuk disewa.
                            </p>
                        </div>
                        <div class="flex items-center ml-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_maintenance" id="maintenanceSwitch" 
                                       value="1" {{ old('is_maintenance', $motor->status == 'maintenance') ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-yellow-500"></div>
                                <span class="ml-3 font-semibold" id="maintenanceLabel">
                                    <span class="px-3 py-1 rounded-full text-xs {{ $motor->status == 'maintenance' ? 'bg-yellow-500 text-white' : 'bg-green-500 text-white' }}">
                                        {{ $motor->status == 'maintenance' ? 'Sedang Maintenance' : 'Normal' }}
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Catatan Maintenance -->
                    <div class="mt-4" id="maintenanceNote" style="display: {{ old('is_maintenance', $motor->status == 'maintenance') ? 'block' : 'none' }};">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Maintenance (Opsional)</label>
                        <textarea class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('maintenance_note') border-red-500 @enderror" 
                                  name="maintenance_note" rows="3" 
                                  placeholder="Contoh: Ganti oli, service rutin, perbaikan rem, dll...">{{ old('maintenance_note', $motor->maintenance_note ?? '') }}</textarea>
                        @error('maintenance_note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Upload Gambar -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-purple-600 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-image mr-2"></i>Foto & Dokumen
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Foto Motor -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Motor</label>
                        
                        @if($motor->photo)
                            <div class="mb-3 relative">
                                <img src="{{ Storage::url($motor->photo) }}" alt="Motor Photo" 
                                     class="w-full rounded-lg border-2 border-gray-200" style="aspect-ratio: 4/3; object-fit: cover;">
                                <div class="absolute top-2 right-2">
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                        <i class="bi bi-check-circle mr-1"></i>Foto Saat Ini
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                </div>
                                <input type="file" class="hidden @error('photo') border-red-500 @enderror" 
                                       name="photo" accept="image/*" id="photoInput">
                            </label>
                        </div>
                        @error('photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="bi bi-info-circle mr-1"></i>Kosongkan jika tidak ingin mengubah foto
                        </p>
                    </div>

                    <!-- Foto Dokumen -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Dokumen (STNK)</label>
                        
                        @if($motor->document)
                            <div class="mb-3 relative">
                                <img src="{{ Storage::url($motor->document) }}" alt="Document Photo" 
                                     class="w-full rounded-lg border-2 border-gray-200" style="max-height: 300px; object-fit: contain;">
                                <div class="absolute top-2 right-2">
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                        <i class="bi bi-check-circle mr-1"></i>Dokumen Saat Ini
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="bi bi-file-earmark-text text-4xl text-gray-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> dokumen</p>
                                    <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                </div>
                                <input type="file" class="hidden @error('document') border-red-500 @enderror" 
                                       name="document" accept="image/*" id="documentInput">
                            </label>
                        </div>
                        @error('document')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="bi bi-info-circle mr-1"></i>Kosongkan jika tidak ingin mengubah dokumen
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Info Motor Saat Ini -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-info-circle mr-2"></i>Info Motor
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <i class="bi bi-tag text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-600">Brand/Model</p>
                            <p class="font-bold text-gray-900">{{ $motor->brand }} {{ $motor->model }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="bi bi-card-text text-gray-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-600">Plat Nomor</p>
                            <p class="font-bold text-gray-900">{{ $motor->plate_number }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                        <i class="bi bi-circle-fill text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-600">Status Saat Ini</p>
                            @php
                                $statusColors = [
                                    'available' => 'text-green-600',
                                    'rented' => 'text-orange-600',
                                    'maintenance' => 'text-gray-600',
                                    'pending_verification' => 'text-yellow-600'
                                ];
                                $statusTexts = [
                                    'available' => 'Tersedia',
                                    'rented' => 'Disewa',
                                    'maintenance' => 'Maintenance',
                                    'pending_verification' => 'Menunggu Verifikasi'
                                ];
                            @endphp
                            <p class="font-bold {{ $statusColors[$motor->status] ?? 'text-gray-600' }}">
                                {{ $statusTexts[$motor->status] ?? ucfirst($motor->status) }}
                            </p>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="bi bi-calendar-plus mr-2"></i>
                            <span>Terdaftar: <strong>{{ $motor->created_at->format('d/m/Y') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-graph-up mr-2"></i>Statistik
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
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-4 space-y-3">
                    <button type="button" onclick="submitFormWithPatch()" class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition inline-flex items-center justify-center">
                        <i class="bi bi-check-lg mr-2 text-xl"></i>Update Motor
                    </button>
                    <a href="{{ route('pemilik.motors') }}" class="w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition inline-flex items-center justify-center">
                        <i class="bi bi-x-lg mr-2"></i>Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Maintenance switch toggle
    const maintenanceSwitch = document.getElementById('maintenanceSwitch');
    const maintenanceLabel = document.getElementById('maintenanceLabel');
    const maintenanceNote = document.getElementById('maintenanceNote');
    
    if (maintenanceSwitch) {
        maintenanceSwitch.addEventListener('change', function() {
            const labelSpan = maintenanceLabel.querySelector('span');
            if (this.checked) {
                labelSpan.textContent = 'Sedang Maintenance';
                labelSpan.className = 'px-3 py-1 rounded-full text-xs bg-yellow-500 text-white';
                maintenanceNote.style.display = 'block';
            } else {
                labelSpan.textContent = 'Normal';
                labelSpan.className = 'px-3 py-1 rounded-full text-xs bg-green-500 text-white';
                maintenanceNote.style.display = 'none';
            }
        });
    }
    
    // Image preview for photo input
    const photoInput = document.getElementById('photoInput');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'w-full rounded-lg border-2 border-blue-500 mt-3';
                    img.style.aspectRatio = '4/3';
                    img.style.objectFit = 'cover';
                    
                    const existingPreview = photoInput.parentElement.parentElement.querySelector('.preview-image');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    img.classList.add('preview-image');
                    photoInput.parentElement.parentElement.appendChild(img);
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    // Image preview for document input
    const documentInput = document.getElementById('documentInput');
    if (documentInput) {
        documentInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'w-full rounded-lg border-2 border-blue-500 mt-3';
                    img.style.maxHeight = '300px';
                    img.style.objectFit = 'contain';
                    
                    const existingPreview = documentInput.parentElement.parentElement.querySelector('.preview-image');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    img.classList.add('preview-image');
                    documentInput.parentElement.parentElement.appendChild(img);
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
});

// Force PATCH method submission
function submitFormWithPatch() {
    const form = document.getElementById('editMotorForm');
    const methodInput = form.querySelector('input[name="_method"]');
    
    // Force set PUT method
    if (methodInput) {
        methodInput.value = 'PUT';
    }
    
    // Submit form
    form.submit();
}
</script>
@endpush