<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'jumlah_kembali',
        'tanggal_kembali',
        'hari_terlambat',
        'kondisi_alat',
        'total_denda',
        'catatan',
        'diproses_oleh',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
        'hari_terlambat' => 'integer',
        'total_denda' => 'decimal:2',
        'jumlah_kembali' => 'integer',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function denda()
    {
        return $this->hasMany(Denda::class);
    }

    public function isBaik(): bool
    {
        return $this->kondisi_alat === 'baik';
    }

    public function isRusak(): bool
    {
        return $this->kondisi_alat === 'rusak';
    }

    public function isHilang(): bool
    {
        return $this->kondisi_alat === 'hilang';
    }

    public function hasDenda(): bool
    {
        return $this->total_denda > 0;
    }
}