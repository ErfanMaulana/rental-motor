// Simple test motor verification
console.log('Simple motor verification loaded');

// Helper functions for status badge
function getStatusBadgeClass(status) {
    switch(status) {
        case 'pending_verification': return 'bg-yellow-100 text-yellow-800';
        case 'available': return 'bg-green-100 text-green-800';
        case 'rented': return 'bg-blue-100 text-blue-800';
        case 'maintenance': return 'bg-gray-100 text-gray-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'pending_verification': return 'Menunggu Verifikasi';
        case 'available': return 'Tersedia';
        case 'rented': return 'Disewa';
        case 'maintenance': return 'Maintenance';
        default: return status;
    }
}

function showMotorDetail(motorId) {
    console.log('showMotorDetail called with ID:', motorId);
    
    if (!motorId) {
        console.error('Motor ID is required');
        showAlert('error', 'ID motor tidak valid');
        return;
    }
    
    // Get content div
    const content = document.getElementById('motorDetailContent');
    if (!content) {
        console.error('motorDetailContent not found');
        return;
    }
    
    // Show loading
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat detail motor...</p>
        </div>
    `;
    
    // Trigger Alpine.js event to open modal
    window.dispatchEvent(new CustomEvent('open-motor-detail'));
    
    // Fetch data
    fetch(`/admin/motors/${motorId}/ajax`)
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error(`Expected JSON but got ${contentType}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Motor data received:', data);
            
            const motor = data.motor;
            const photoUrl = motor.photo ? `/storage/${motor.photo}` : null;
            
            content.innerHTML = `
                ${photoUrl ? 
                    `<img src="${photoUrl}" class="rounded-lg mb-2 mx-auto" alt="${motor.brand} ${motor.model || ''}" style="width: 75%; height: auto; aspect-ratio: 4/3; object-fit: contain; background-color: #f3f4f6;">` :
                    `<div class="bg-gray-100 rounded-lg flex items-center justify-center mb-2 mx-auto" style="width: 75%; aspect-ratio: 4/3;">
                        <i class="bi bi-motorcycle text-gray-400 text-4xl"></i>
                    </div>`
                }
                
                <div class="space-y-1">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 leading-tight">${motor.brand} ${motor.model || ''}</h3>
                        <div class="flex gap-1 mt-1">
                            <span class="px-2 py-0.5 text-[10px] bg-blue-100 text-blue-700 rounded">${motor.type_cc}</span>
                            <span class="px-2 py-0.5 text-[10px] ${getStatusBadgeClass(motor.status)} rounded">${getStatusText(motor.status)}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div><span class="text-gray-500">Tahun:</span> <span class="font-semibold">${motor.year || '-'}</span></div>
                        <div><span class="text-gray-500">Warna:</span> <span class="font-semibold">${motor.color || '-'}</span></div>
                        <div><span class="text-gray-500">Plat:</span> <span class="font-semibold">${motor.plate_number}</span></div>
                        <div><span class="text-gray-500">Pemilik:</span> <span class="font-semibold">${motor.owner.name}</span></div>
                    </div>
                    
                    ${motor.description ? `
                        <div class="border-t border-gray-200 pt-1.5">
                            <p class="text-[10px] text-gray-500 mb-0.5">Deskripsi</p>
                            <p class="text-xs text-gray-700">${motor.description}</p>
                        </div>
                    ` : ''}
                </div>
                
                ${motor.rental_rate ? `
                    <div class="mt-2 border-t border-gray-200 pt-2">
                        <p class="text-xs text-gray-500 mb-1"><i class="bi bi-cash-stack mr-1"></i>Harga Sewa</p>
                        <div class="flex gap-2 text-xs">
                            <div class="flex-1 bg-blue-50 rounded px-2 py-1.5 text-center">
                                <p class="text-[10px] text-gray-600 mb-0.5">Harian</p>
                                <p class="text-sm font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</p>
                            </div>
                            <div class="flex-1 bg-blue-50 rounded px-2 py-1.5 text-center">
                                <p class="text-[10px] text-gray-600 mb-0.5">Mingguan</p>
                                <p class="text-sm font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.weekly_rate)}</p>
                            </div>
                            <div class="flex-1 bg-blue-50 rounded px-2 py-1.5 text-center">
                                <p class="text-[10px] text-gray-600 mb-0.5">Bulanan</p>
                                <p class="text-sm font-bold text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.monthly_rate)}</p>
                            </div>
                        </div>
                    </div>
                ` : `
                    <div class="mt-2 bg-yellow-50 border border-yellow-200 rounded px-3 py-2">
                        <span class="text-xs text-yellow-800"><i class="bi bi-exclamation-triangle mr-1"></i>Harga sewa belum ditetapkan</span>
                    </div>
                `}
                
                ${data.stats ? `
                    <div class="mt-2 border-t border-gray-200 pt-2">
                        <p class="text-xs text-gray-500 mb-1"><i class="bi bi-graph-up mr-1"></i>Statistik</p>
                        <div class="flex gap-2">
                            <div class="flex-1 bg-blue-500 text-white rounded px-2 py-2 text-center">
                                <p class="text-base font-bold leading-none">${data.stats.total_bookings}</p>
                                <p class="text-[10px] mt-1">Total</p>
                            </div>
                            <div class="flex-1 bg-yellow-500 text-white rounded px-2 py-2 text-center">
                                <p class="text-base font-bold leading-none">${data.stats.active_bookings}</p>
                                <p class="text-[10px] mt-1">Aktif</p>
                            </div>
                            <div class="flex-1 bg-green-500 text-white rounded px-2 py-2 text-center">
                                <p class="text-base font-bold leading-none">${data.stats.completed_bookings}</p>
                                <p class="text-[10px] mt-1">Selesai</p>
                            </div>
                            <div class="flex-1 bg-purple-500 text-white rounded px-2 py-2 text-center">
                                <p class="text-xs font-bold leading-none">Rp ${(data.stats.total_earnings / 1000).toFixed(0)}k</p>
                                <p class="text-[10px] mt-1">Revenue</p>
                            </div>
                        </div>
                    </div>
                ` : ''}
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-danger">Gagal Memuat Detail</h5>
                    <p class="text-muted">Error: ${error.message}</p>
                    <button class="btn btn-primary" onclick="showMotorDetail(${motorId})">Coba Lagi</button>
                </div>
            `;
        });
}

function directVerifyMotor(motorId) {
    console.log('directVerifyMotor called with ID:', motorId);
    
    if (!motorId) {
        console.error('Motor ID is required');
        showAlert('error', 'ID motor tidak valid');
        return;
    }
    
    // Show pricing modal
    showPricingModal(motorId);
}

function showPricingModal(motorId) {
    const modalHTML = `
        <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-check-circle me-2"></i>Verifikasi Motor & Set Harga Sewa
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="verifyPricingForm">
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Perhatian:</strong> Setelah motor diverifikasi dan harga ditetapkan, motor akan tersedia untuk disewa oleh penyewa.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="daily_rate" class="form-label">Tarif Harian <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="daily_rate" 
                                               name="daily_rate" 
                                               min="10000"
                                               max="1000000"
                                               step="1000"
                                               placeholder="450000"
                                               required>
                                    </div>
                                    <div class="form-text">Minimal Rp 10.000 - Maksimal Rp 1.000.000</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="weekly_rate" class="form-label">Tarif Mingguan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control bg-light" 
                                               id="weekly_rate" 
                                               name="weekly_rate"
                                               readonly
                                               placeholder="Otomatis dengan diskon 10%">
                                    </div>
                                    <div class="form-text text-success">Auto: diskon 10% dari 7x tarif harian</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="monthly_rate" class="form-label">Tarif Bulanan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control bg-light" 
                                               id="monthly_rate" 
                                               name="monthly_rate"
                                               readonly
                                               placeholder="Otomatis dengan diskon 20%">
                                    </div>
                                    <div class="form-text text-success">Auto: diskon 20% dari 30x tarif harian</div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-lightbulb text-warning me-2"></i>Tips Penetapan Harga
                                            </h6>
                                            <ul class="mb-2 small">
                                                <li>Motor 100cc-125cc: Rp 50.000 - 80.000/hari</li>
                                                <li>Motor 150cc: Rp 80.000 - 120.000/hari</li>
                                                <li>Motor 250cc+: Rp 120.000 - 200.000/hari</li>
                                                <li>Motor Premium/Sport: Rp 300.000 - 1.000.000/hari</li>
                                                <li>Pertimbangkan kondisi, umur, dan brand motor</li>
                                            </ul>
                                            <div class="alert alert-warning py-2 mb-0">
                                                <small><strong>Catatan:</strong> Tarif mingguan dan bulanan akan otomatis dihitung berdasarkan tarif harian (diskon 10% untuk mingguan, diskon 20% untuk bulanan).</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-success" id="submitVerifyBtn">
                                <i class="bi bi-check-circle me-2"></i>Verifikasi & Set Harga
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('pricingModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Setup auto calculation
    setupPricingCalculation();
    
    // Setup form submission
    const form = document.getElementById('verifyPricingForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitVerification(motorId);
    });
    
    // Show modal
    const modalElement = document.getElementById('pricingModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    // Remove modal from DOM after hide
    modalElement.addEventListener('hidden.bs.modal', () => {
        modalElement.remove();
    });
}

function submitVerification(motorId) {
    const form = document.getElementById('verifyPricingForm');
    const submitBtn = document.getElementById('submitVerifyBtn');
    const originalBtnText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    
    // Get form data
    const formData = new FormData(form);
    const data = {
        daily_rate: formData.get('daily_rate'),
        weekly_rate: formData.get('weekly_rate'),
        monthly_rate: formData.get('monthly_rate'),
        _method: 'PATCH'
    };
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        showAlert('error', 'CSRF token tidak ditemukan');
        return;
    }
    
    // Send request
    fetch(`/admin/motors/${motorId}/verify`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Verification success:', data);
        
        // Close modal
        const modalElement = document.getElementById('pricingModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
        
        // Show success message
        showAlert('success', 'Motor berhasil diverifikasi dan harga sewa telah ditetapkan!');
        
        // Reload page after short delay
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    })
    .catch(error => {
        console.error('Verification error:', error);
        showAlert('error', 'Gagal memverifikasi motor: ' + error.message);
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const iconClass = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
    
    const alertHtml = `
        <div class="custom-alert position-fixed top-0 end-0 m-3 ${bgClass} text-white px-4 py-3 rounded shadow-lg d-flex align-items-center justify-content-between" style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="bi ${iconClass} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close btn-close-white ms-3" onclick="this.parentElement.remove()"></button>
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

function setupPricingCalculation() {
    const dailyInput = document.getElementById('daily_rate');
    const weeklyInput = document.getElementById('weekly_rate');
    const monthlyInput = document.getElementById('monthly_rate');
    
    if (!dailyInput || !weeklyInput || !monthlyInput) {
        console.error('Pricing inputs not found');
        return;
    }
    
    function calculateRates() {
        const dailyRate = parseInt(dailyInput.value) || 0;
        
        if (dailyRate > 0) {
            // Validasi maksimal Rp 1.000.000 per hari
            if (dailyRate > 1000000) {
                dailyInput.value = 1000000;
                alert('Maksimal harga harian adalah Rp 1.000.000');
                return;
            }
            
            // Kalkulasi otomatis tarif mingguan (diskon 10%)
            const weeklyRate = Math.floor(dailyRate * 7 * 0.9);
            weeklyInput.value = weeklyRate;
            
            // Kalkulasi otomatis tarif bulanan (diskon 20%)
            const monthlyRate = Math.floor(dailyRate * 30 * 0.8);
            monthlyInput.value = monthlyRate;
            
            console.log('Auto calculated:', {
                daily: dailyRate,
                weekly: weeklyRate,
                monthly: monthlyRate
            });
        } else {
            // Reset jika tarif harian kosong
            weeklyInput.value = '';
            monthlyInput.value = '';
        }
    }
    
    // Event listener untuk input tarif harian
    dailyInput.addEventListener('input', calculateRates);
    dailyInput.addEventListener('change', calculateRates);
    dailyInput.addEventListener('keyup', calculateRates);
    
    // Format angka dengan pemisah ribuan saat blur
    dailyInput.addEventListener('blur', function() {
        if (this.value) {
            const value = parseInt(this.value.replace(/\./g, ''));
            this.value = value;
            calculateRates();
        }
    });
    
    // Mencegah input manual di tarif mingguan dan bulanan
    weeklyInput.addEventListener('focus', function() {
        this.blur();
        dailyInput.focus();
    });
    
    monthlyInput.addEventListener('focus', function() {
        this.blur();
        dailyInput.focus();
    });
    
    // Set placeholder yang jelas
    weeklyInput.setAttribute('placeholder', 'Otomatis dengan diskon 10%');
    monthlyInput.setAttribute('placeholder', 'Otomatis dengan diskon 20%');
    weeklyInput.setAttribute('readonly', 'true');
    monthlyInput.setAttribute('readonly', 'true');
}

// Test if bootstrap is available
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
});