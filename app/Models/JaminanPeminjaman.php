<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JaminanPeminjaman extends Model
{
    protected $table = 'jaminan_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'jenis',
        'keterangan',
        'nominal',
        'bukti',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function isMenunggu(): bool
    {
        return $this->status === 'menunggu';
    }

    public function isDiterima(): bool
    {
        return $this->status === 'diterima';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }
}
