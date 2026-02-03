@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-header">
    <h2 class="auth-title">Lupa Password?</h2>
    <p class="auth-subtitle" id="subtitle">Tidak masalah! Masukkan email Anda dan password baru yang Anda inginkan.</p>
</div>

<!-- Alert Messages -->
<div id="alertContainer"></div>

<!-- Step 1: Email Form -->
<div id="stepEmail">
    <div class="form-group">
        <label class="form-label" for="email">
            <i class="bi bi-envelope me-2"></i>Email
        </label>
        <input 
            id="email" 
            class="form-control" 
            type="email" 
            required 
            autofocus
            placeholder="Masukkan email Anda"
        />
        <div class="invalid-feedback" id="emailError"></div>
    </div>

    <button type="button" class="btn-primary" id="verifyEmailBtn" onclick="verifyEmail()">
        <i class="bi bi-arrow-right me-2"></i>
        Lanjut ke Ganti Password
    </button>
</div>

<!-- Step 2: Password Form -->
<div id="stepPassword" style="display: none;">
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle me-2"></i>
        Email <strong id="displayEmail"></strong> ditemukan. Masukkan password baru Anda.
    </div>

    <div class="form-group">
        <label class="form-label" for="password">
            <i class="bi bi-lock me-2"></i>Password Baru
        </label>
        <div class="password-wrapper">
            <input 
                id="password" 
                class="form-control" 
                type="password"
                minlength="8"
                placeholder="Minimal 8 karakter"
            />
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="togglePasswordIcon"></i>
            </button>
        </div>
        <div class="invalid-feedback" id="passwordError"></div>
    </div>

    <div class="form-group">
        <label class="form-label" for="password_confirmation">
            <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password
        </label>
        <div class="password-wrapper">
            <input 
                id="password_confirmation" 
                class="form-control" 
                type="password"
                minlength="8"
                placeholder="Ketik ulang password baru"
            />
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
            </button>
        </div>
        <div class="invalid-feedback" id="passwordConfirmError"></div>
    </div>

    <button type="button" class="btn-primary" id="resetPasswordBtn" onclick="resetPassword()">
        <i class="bi bi-check-circle me-2"></i>
        Ganti Password
    </button>

    <button type="button" class="btn btn-secondary mt-2" onclick="backToEmail()">
        <i class="bi bi-arrow-left me-2"></i>
        Ubah Email
    </button>
</div>

<div class="auth-links">
    <div>
        Ingat password Anda?
        <a href="{{ route('login') }}" class="auth-link">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
        </a>
    </div>
</div>

<style>
.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 5px 10px;
}

.password-toggle:hover {
    color: #495057;
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

.alert-danger {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}

.alert-info {
    background-color: #cff4fc;
    color: #055160;
    border: 1px solid #b6effb;
}

.spinner-border {
    width: 1rem;
    height: 1rem;
    border: 2px solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    display: inline-block;
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}
</style>

<script>
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alertContainer');
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
    
    alertContainer.innerHTML = `
        <div class="alert ${alertClass}">
            <i class="bi bi-${icon} me-2"></i>
            ${message}
        </div>
    `;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}

async function verifyEmail() {
    const email = document.getElementById('email').value;
    const verifyEmailBtn = document.getElementById('verifyEmailBtn');
    
    // Validation
    if (!email) {
        showAlert('Silakan masukkan email Anda', 'error');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showAlert('Format email tidak valid', 'error');
        return;
    }
    
    // Loading state
    verifyEmailBtn.disabled = true;
    verifyEmailBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...';
    
    try {
        const response = await fetch('{{ route('password.email') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('displayEmail').textContent = email;
            document.getElementById('stepEmail').style.display = 'none';
            document.getElementById('stepPassword').style.display = 'block';
            document.getElementById('subtitle').textContent = 'Masukkan password baru Anda';
        } else {
            showAlert(data.message, 'error');
        }
    } catch (error) {
        showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
    } finally {
        verifyEmailBtn.disabled = false;
        verifyEmailBtn.innerHTML = '<i class="bi bi-arrow-right me-2"></i>Lanjut ke Ganti Password';
    }
}

async function resetPassword() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');
    
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validation
    if (!password || password.length < 8) {
        document.getElementById('password').classList.add('is-invalid');
        document.getElementById('passwordError').textContent = 'Password minimal 8 karakter';
        return;
    }
    
    if (password !== passwordConfirmation) {
        document.getElementById('password_confirmation').classList.add('is-invalid');
        document.getElementById('passwordConfirmError').textContent = 'Konfirmasi password tidak sesuai';
        return;
    }
    
    // Loading state
    resetPasswordBtn.disabled = true;
    resetPasswordBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengganti Password...';
    
    try {
        const response = await fetch('{{ route('password.verify-reset') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email: email,
                password: password,
                password_confirmation: passwordConfirmation
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert(data.message, 'success');
            setTimeout(() => {
                window.location.href = '{{ route('login') }}';
            }, 2000);
        } else {
            showAlert(data.message, 'error');
        }
    } catch (error) {
        showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
    } finally {
        resetPasswordBtn.disabled = false;
        resetPasswordBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Ganti Password';
    }
}

function backToEmail() {
    document.getElementById('stepEmail').style.display = 'block';
    document.getElementById('stepPassword').style.display = 'none';
    document.getElementById('subtitle').textContent = 'Tidak masalah! Masukkan email Anda dan password baru yang Anda inginkan.';
    document.getElementById('password').value = '';
    document.getElementById('password_confirmation').value = '';
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('toggle' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1) + 'Icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Enter key support
document.getElementById('email').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        verifyEmail();
    }
});

document.getElementById('password_confirmation').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        resetPassword();
    }
});
</script>
@endsection
