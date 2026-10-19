<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    
    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'status',
        'disetujui_oleh',
        'tanggal_disetujui',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_disetujui' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function jaminan()
    {
        return $this->hasOne(JaminanPeminjaman::class);
    }

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class);
    }

    public function isMenunggu(): bool
    {
        return $this->status === 'menunggu';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDipinjam(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function isDikembalikan(): bool
    {
        return $this->status === 'dikembalikan';
    }

    public function isDibatalkan(): bool
    {
        return $this->status === 'dibatalkan';
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'menunggu' && $this->jaminan?->status === 'diterima';
    }

    public function totalHarga(): float
    {
        return $this->detailPeminjaman->sum('subtotal');
    }

    public function totalHari(): int
    {
        return $this->tanggal_pinjam->diffInDays($this->tanggal_jatuh_tempo) + 1;
    }
}
