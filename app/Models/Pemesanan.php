<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'pemesanan_id';

    protected $fillable = [
        'customer_id',
        'workspace_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status_pemesanan',
        'transaction_id'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    /**
     * Mendapatkan status pemesanan yang lebih bersahabat
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status_pemesanan) {
            'MENUNGGU_PEMBAYARAN' => 'Menunggu Pembayaran',
            'DIBAYAR' => 'Terbayar',
            'BATAL' => 'Dibatalkan',
            'SELESAI' => 'Selesai',
            default => $this->status_pemesanan
        };
    }
}
