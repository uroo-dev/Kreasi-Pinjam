<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'modul',
        'aksi',
        'deskripsi',
        'data_lama',
        'data_baru',
        'ip_address',
    ];

    protected $casts = [
        'data_lama' => 'json',
        'data_baru' => 'json',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $modul,
        string $aksi,
        string $deskripsi,
        ?int $userId = null,
        ?array $dataLama = null,
        ?array $dataBaru = null,
        ?string $ipAddress = null
    ): void {
        self::create([
            'user_id' => $userId,
            'modul' => $modul,
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => $ipAddress,
        ]);
    }
}