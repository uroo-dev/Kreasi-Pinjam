<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alat extends Model
{
    use SoftDeletes;

    protected $table = 'alat';

    protected $fillable = [
        'kategori_id',
        'kode_alat',
        'nama_alat',
        'deskripsi',
        'stok_total',
        'stok_tersedia',
        'harga_sewa',
        'status',
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'tersedia' && $this->stok_tersedia > 0;
    }

    public function updateStokTersedia(): void
    {
        $dipinjam = $this->detailPeminjaman()
            ->whereHas('peminjaman', function ($query) {
                $query->whereIn('status', ['disetujui', 'dipinjam']);
            })
            ->sum('jumlah');

        $this->stok_tersedia = $this->stok_total - $dipinjam;
        $this->status = $this->stok_tersedia > 0 ? 'tersedia' : 'tidak_tersedia';
        $this->save();
    }
}