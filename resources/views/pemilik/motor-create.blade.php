@extends('layouts.fann')

@section('title', 'Tambah Motor Baru')

@section('content')
<!-- Content Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
        <i class="bi bi-motorcycle mr-3"></i>Tambah Motor Baru
    </h1>
    <p class="text-gray-600 mt-1">Daftarkan motor Anda untuk disewakan kepada penyewa</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="bi bi-plus-circle mr-2"></i>
                    Informasi Motor
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('pemilik.motor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Brand Motor -->
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                                Merk Motor <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('brand') border-red-500 @enderror" id="brand" name="brand" required>
                                <option value="">Pilih Merk Motor</option>
                                <option value="Honda" {{ old('brand') == 'Honda' ? 'selected' : '' }}>Honda</option>
                                <option value="Yamaha" {{ old('brand') == 'Yamaha' ? 'selected' : '' }}>Yamaha</option>
                                <option value="Kawasaki" {{ old('brand') == 'Kawasaki' ? 'selected' : '' }}>Kawasaki</option>
                                <option value="Suzuki" {{ old('brand') == 'Suzuki' ? 'selected' : '' }}>Suzuki</option>
                            </select>
                            @error('brand')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Motor -->
                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Motor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('model') border-red-500 @enderror" 
                                   id="model" 
                                   name="model" 
                                   value="{{ old('model') }}"
                                   placeholder="Contoh: Beat, Vario, Ninja"
                                   required>
                            @error('model')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Masukkan nama/model motor</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <!-- CC Motor -->
                        <div>
                            <label for="type_cc" class="block text-sm font-medium text-gray-700 mb-2">
                                Kapasitas Mesin <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type_cc') border-red-500 @enderror" id="type_cc" name="type_cc" required>
                                <option value="">Pilih CC</option>
                                <option value="100cc" {{ old('type_cc') == '100cc' ? 'selected' : '' }}>100cc</option>
                                <option value="110cc" {{ old('type_cc') == '110cc' ? 'selected' : '' }}>110cc</option>
                                <option value="125cc" {{ old('type_cc') == '125cc' ? 'selected' : '' }}>125cc</option>
                                <option value="150cc" {{ old('type_cc') == '150cc' ? 'selected' : '' }}>150cc</option>
                                <option value="160cc" {{ old('type_cc') == '160cc' ? 'selected' : '' }}>160cc</option>
                                <option value="250cc" {{ old('type_cc') == '250cc' ? 'selected' : '' }}>250cc</option>
                                <option value="400cc" {{ old('type_cc') == '400cc' ? 'selected' : '' }}>400cc</option>
                                <option value="500cc" {{ old('type_cc') == '500cc' ? 'selected' : '' }}>500cc</option>
                                <option value="600cc" {{ old('type_cc') == '600cc' ? 'selected' : '' }}>600cc</option>
                            </select>
                            @error('type_cc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Motor -->
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-500 @enderror" id="year" name="year" required size="1" style="max-height: 300px; overflow-y: auto;">
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= 2010; $i--)
                                    <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warna Motor -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                                Warna <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color') }}"
                                   placeholder="Contoh: Merah, Hitam"
                                   required>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Plat Nomor -->
                    <div class="mt-4">
                        <label for="plate_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Plat Nomor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plate_number') border-red-500 @enderror" 
                               id="plate_number" 
                               name="plate_number" 
                               value="{{ old('plate_number') }}"
                               placeholder="Contoh: B 1234 ABC"
                               required>
                        @error('plate_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Masukkan plat nomor motor yang valid</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Foto Motor -->
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Motor
                            </label>
                            <input type="file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror" 
                                   id="photo" 
                                   name="photo"
                                   accept="image/*">
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Upload foto motor yang menarik (maksimal 2MB)</p>
                            
                            <!-- Preview Image -->
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="preview" src="" alt="Preview" class="rounded-lg border border-gray-200 max-h-48">
                            </div>
                        </div>

                        <!-- Foto Dokumen -->
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Dokumen
                            </label>
                            <input type="file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('document') border-red-500 @enderror" 
                                   id="document" 
                                   name="document"
                                   accept="image/*">
                            @error('document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Upload foto STNK/dokumen motor (maksimal 2MB)</p>
                            
                            <!-- Preview Document -->
                            <div id="documentPreview" class="mt-3 hidden">
                                <img id="docPreview" src="" alt="Document Preview" class="rounded-lg border border-gray-200 max-h-48">
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Motor
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Deskripsikan kondisi motor, fitur, dan hal menarik lainnya...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Berikan deskripsi yang menarik untuk menarik penyewa</p>
                    </div>

                    <!-- Info Harga Sewa -->
                    <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                        <div class="flex">
                            <i class="bi bi-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div>
                                <p class="font-semibold text-blue-900">Informasi Penting:</p>
                                <p class="text-blue-700 text-sm">Harga sewa motor akan ditentukan oleh admin setelah proses verifikasi selesai. Admin akan menetapkan harga yang sesuai dengan kondisi dan spesifikasi motor Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('pemilik.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg transition">
                            <i class="bi bi-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            <i class="bi bi-check-circle mr-2"></i>Daftarkan Motor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tips Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Tips Success Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="bi bi-lightbulb text-yellow-500 mr-2"></i>
                    Tips Sukses
                </h6>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-camera text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Foto Berkualitas</p>
                        <p class="text-xs text-gray-600 mt-1">Upload foto yang jelas dan menarik dari berbagai sudut</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-pencil text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Deskripsi Detail</p>
                        <p class="text-xs text-gray-600 mt-1">Tulis deskripsi yang detail dan jujur tentang kondisi motor</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-currency-dollar text-cyan-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Harga Otomatis</p>
                        <p class="text-xs text-gray-600 mt-1">Harga sewa akan ditentukan oleh admin setelah verifikasi</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="bi bi-shield-check text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Data Valid</p>
                        <p class="text-xs text-gray-600 mt-1">Pastikan semua data yang dimasukkan benar dan valid</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="bi bi-info-circle text-blue-500 mr-2"></i>
                    Proses Selanjutnya
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 relative">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                1
                            </div>
                            <div class="absolute top-8 left-1/2 -ml-px h-full w-0.5 bg-gray-200"></div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">Pendaftaran Motor</p>
                            <p class="text-xs text-gray-600 mt-1">Isi formulir dengan lengkap dan upload foto</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 relative">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                2
                            </div>
                            <div class="absolute top-8 left-1/2 -ml-px h-full w-0.5 bg-gray-200"></div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">Verifikasi Admin</p>
                            <p class="text-xs text-gray-600 mt-1">Admin akan mereview dan verifikasi motor Anda</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                3
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">Motor Aktif</p>
                            <p class="text-xs text-gray-600 mt-1">Motor siap untuk disewakan setelah disetujui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Preview foto motor
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
    });

    // Preview foto dokumen
    document.getElementById('document').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('docPreview').src = e.target.result;
                document.getElementById('documentPreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('documentPreview').classList.add('hidden');
        }
    });

    // Auto-format plat nomor
    document.getElementById('plate_number').addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase();
        // Basic formatting for Indonesian plate number
        value = value.replace(/[^A-Z0-9\s]/g, '');
        e.target.value = value;
    });
</script>
@endpush