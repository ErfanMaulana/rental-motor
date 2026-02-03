<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'method',
        'payment_method',
        'status',
        'paid_at',
        'payment_proof',
        'payment_notes',
        'notes',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper methods
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    public function getFormattedMethodAttribute()
    {
        $methods = [
            'bank_transfer' => 'Transfer Bank',
            'transfer_bank' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'cash' => 'Cash/Tunai',
            'credit_card' => 'Kartu Kredit'
        ];
        
        return $methods[$this->method] ?? ucwords(str_replace('_', ' ', $this->method));
    }

    public function getFormattedPaymentMethodAttribute()
    {
        // Prioritas: gunakan payment_method dulu (dana, gopay, shopeepay, bank)
        // Fallback ke method (bank_transfer, e_wallet, cash, credit_card)
        if ($this->payment_method) {
            $methods = [
                'dana' => 'DANA',
                'gopay' => 'GoPay',
                'shopeepay' => 'ShopeePay',
                'bank' => 'Transfer Bank'
            ];
            return $methods[$this->payment_method] ?? ucwords($this->payment_method);
        }
        
        // Fallback untuk payment lama
        $methods = [
            'bank_transfer' => 'Transfer Bank',
            'transfer_bank' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'cash' => 'Cash/Tunai',
            'credit_card' => 'Kartu Kredit'
        ];
        
        return $methods[$this->method] ?? ucwords(str_replace('_', ' ', $this->method));
    }

    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? asset('storage/' . $this->payment_proof) : null;
    }
}
