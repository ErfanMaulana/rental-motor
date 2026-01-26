@extends('layouts.fann')

@section('title', 'Detail Motor')

@section('content')
<div style="max-width: 1280px; margin: 0 auto; padding: 1.5rem;">
    <!-- Header -->
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827; display: flex; align-items: center;">
            <i class="bi bi-motorcycle" style="margin-right: 0.75rem;"></i>Detail Motor
        </h1>
        <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Informasi lengkap motor untuk verifikasi</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
        @if($motor->status === 'pending_verification')
        <!-- Verification Form -->
        <div style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; padding: 1.5rem;">
            <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.125rem; font-weight: 600; color: #111827; display: flex; align-items: center;">
                    <i class="bi bi-check-circle" style="margin-right: 0.5rem;"></i>Verifikasi Motor
                </h2>
            </div>

            <!-- Alert Info -->
            <div style="background-color: #dbeafe; border: 1px solid #93c5fd; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: flex-start;">
                    <i class="bi bi-info-circle" style="color: #2563eb; font-size: 1.25rem; margin-right: 0.75rem; margin-top: 0.125rem;"></i>
                    <div>
                        <h6 style="font-weight: 600; color: #1e3a8a; margin-bottom: 0.25rem;">Perhatian:</h6>
                        <p style="font-size: 0.875rem; color: #1e40af; margin: 0;">Setelah motor diverifikasi dan harga ditetapkan, motor akan tersedia untuk disewa oleh penyewa.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.motor.verify', $motor->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <!-- Motor Info -->
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #374151; margin: 0;"><strong>{{ $motor->brand }} {{ $motor->model }}</strong></p>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">{{ $motor->type_cc }} | {{ $motor->plate_number }}</p>
                </div>

                <!-- Tarif Harian -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="daily_rate" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        <i class="bi bi-currency-dollar" style="margin-right: 0.25rem;"></i>Tarif Harian *
                    </label>
                    <div style="display: flex; align-items: stretch;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-right: 0; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; font-size: 0.875rem; color: #374151; font-weight: 500;">Rp</span>
                        <input 
                            type="number" 
                            id="daily_rate" 
                            name="daily_rate" 
                            min="10000" 
                            max="1000000"
                            step="1000"
                            value="450000"
                            required
                            style="flex: 1; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; font-size: 0.875rem; outline: none; font-family: inherit;">
                    </div>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.5rem 0 0 0;">Minimal Rp 10.000 - Maksimal Rp 1.000.000</p>
                </div>

                <!-- Tarif Mingguan -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Tarif Mingguan</label>
                    <div style="padding: 0.625rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                        Otomatis dengan diskon 10%
                    </div>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.5rem 0 0 0;">Auto: diskon 10% dari 7x tarif harian</p>
                </div>

                <!-- Tarif Bulanan -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Tarif Bulanan</label>
                    <div style="padding: 0.625rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                        Otomatis dengan diskon 20%
                    </div>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.5rem 0 0 0;">Auto: diskon 20% dari 30x tarif harian</p>
                </div>

                <!-- Tips Box -->
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                    <h6 style="font-weight: 600; color: #374151; margin: 0 0 0.5rem 0; display: flex; align-items: center;">
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

                <!-- Catatan -->
                <p style="font-size: 0.75rem; color: #6b7280; font-style: italic; margin: 0 0 1.5rem 0;">
                    <strong>Catatan:</strong> Tarif mingguan dan bulanan akan otomatis dihitung berdasarkan tarif harian (diskon 10% untuk mingguan, diskon 20% untuk bulanan).
                </p>

                <!-- Buttons -->
                <div style="display: flex; justify-content: space-between; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <a href="{{ route('admin.motors') }}" 
                       style="display: inline-flex; align-items: center; padding: 0.625rem 1.5rem; background-color: #f3f4f6; color: #374151; font-weight: 500; border-radius: 0.5rem; text-decoration: none; transition: background-color 0.2s; border: none; cursor: pointer;"
                       onmouseover="this.style.backgroundColor='#e5e7eb'" 
                       onmouseout="this.style.backgroundColor='#f3f4f6'">
                        <i class="bi bi-arrow-left" style="margin-right: 0.5rem;"></i>Batal
                    </a>
                    <button type="submit" 
                            style="display: inline-flex; align-items: center; padding: 0.625rem 1.5rem; background-color: #16a34a; color: white; font-weight: 500; border-radius: 0.5rem; border: none; cursor: pointer; transition: background-color 0.2s;"
                            onmouseover="this.style.backgroundColor='#15803d'" 
                            onmouseout="this.style.backgroundColor='#16a34a'">
                        <i class="bi bi-check-circle" style="margin-right: 0.5rem;"></i>Verifikasi & Set Harga
                    </button>
                </div>
            </form>
        </div>
        @else
        <!-- Motor Already Verified -->
        <div style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; padding: 1.5rem;">
            <div style="text-align: center; padding: 2rem;">
                <i class="bi bi-check-circle" style="font-size: 3rem; color: #16a34a; margin-bottom: 1rem; display: block;"></i>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Motor Sudah Terverifikasi</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1.5rem;">Motor ini sudah diverifikasi dan tersedia untuk disewa.</p>
                <a href="{{ route('admin.motors') }}" 
                   style="display: inline-flex; align-items: center; padding: 0.625rem 1.5rem; background-color: #f3f4f6; color: #374151; font-weight: 500; border-radius: 0.5rem; text-decoration: none;"
                   onmouseover="this.style.backgroundColor='#e5e7eb'" 
                   onmouseout="this.style.backgroundColor='#f3f4f6'">
                    <i class="bi bi-arrow-left" style="margin-right: 0.5rem;"></i>Kembali ke Daftar Motor
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
