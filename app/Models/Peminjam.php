<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjam extends Model
{
    use SoftDeletes;

    protected $table = 'peminjam';

    protected $fillable = [
        'user_id',
        'no_identitas',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'foto',
        'institusi',
        'pekerjaan',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id', 'user_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }
}