<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alat';
    protected $fillable = [
        'kategori_id',
        'nama',
        'deskripsi',
        'jumlah',
        'foto',
        'is_active'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
