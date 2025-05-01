<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'payment_method',
        'payment_status',
        'midtrans_payment_id',
        'midtrans_payment_url',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the related pemesanan.
     */
    public function pemesanan(): HasOne
    {
        return $this->hasOne(Pemesanan::class, 'transaction_id', 'id');
    }

    /**
     * Mendapatkan nama metode pembayaran yang lebih bersahabat
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'credit_card' => 'Kartu Kredit',
            default => $this->payment_method
        };
    }

    /**
     * Mendapatkan status pembayaran yang lebih bersahabat
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'PAID' => 'Berhasil',
            'PENDING' => 'Menunggu Pembayaran',
            'FAILED' => 'Gagal',
            default => $this->payment_status
        };
    }
}
