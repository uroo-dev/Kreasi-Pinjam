<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $table = 'denda';

    protected $fillable = [
        'user_id',
        'pengembalian_id',
        'jenis_denda',
        'nominal',
        'dibayar',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'dibayar' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class);
    }

    public function isBelumLunas(): bool
    {
        return $this->status === 'belum_lunas';
    }

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }

    public function sisaBayar(): float
    {
        return $this->nominal - $this->dibayar;
    }

    public function updateStatus(): void
    {
        $this->status = $this->dibayar >= $this->nominal ? 'lunas' : 'belum_lunas';
        $this->save();
    }

    public function bayar(float $jumlah): void
    {
        $this->dibayar += $jumlah;
        $this->updateStatus();
    }
}
