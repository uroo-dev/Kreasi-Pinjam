<?php

namespace App\Support;

use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User;

class Tanggungan
{
    /**
     * Pemeriksaan tanggungan peminjam. Peminjam yang memiliki
     * alat yang belum dikembalikan melewati jatuh tempo, atau denda
     * yang belum lunas, tidak diizinkan mengajukan peminjaman baru.
     *
     * @return array{ada: bool, melewati_jatuh_tempo: int, denda_belum_lunas: float, alat_aktif: int}
     */
    public static function cek(User|int $user): array
    {
        $userId = $user instanceof User ? $user->id : $user;

        $melewatiJatuhTempo = Peminjaman::query()
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<', now())
            ->count();

        $dendaBelumLunas = Denda::query()
            ->where('user_id', $userId)
            ->where('status', 'belum_lunas')
            ->get()
            ->sum(fn ($denda) => $denda->nominal - $denda->dibayar);

        $alatAktif = Peminjaman::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->with('details')
            ->get()
            ->flatMap(fn ($p) => $p->details)
            ->sum('jumlah');

        return [
            'ada' => $melewatiJatuhTempo > 0 || $dendaBelumLunas > 0,
            'melewati_jatuh_tempo' => $melewatiJatuhTempo,
            'denda_belum_lunas' => (float) $dendaBelumLunas,
            'alat_aktif' => (int) $alatAktif,
        ];
    }

    /**
     * Batasi pengajuan bila peminjam masih memiliki tanggungan.
     */
    public static function tidakBolehMengajukan(User $user): bool
    {
        return self::cek($user)['ada'];
    }
}
