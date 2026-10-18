<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'jumlah',
        'harga_sewa',
        'subtotal',
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public static function calculateSubtotal(int $jumlah, float $hargaSewa, int $totalHari): float
    {
        return $jumlah * $hargaSewa * $totalHari;
    }
}