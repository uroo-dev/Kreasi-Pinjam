<?php

namespace App\Support;

class Status
{
    /**
     * Warna badge status peminjaman.
     */
    public static function peminjaman(?string $status): string
    {
        return match ($status) {
            'menunggu' => 'bg-amber-50 text-amber-700',
            'disetujui' => 'bg-blue-50 text-blue-700',
            'ditolak', 'dibatalkan' => 'bg-red-50 text-red-700',
            'dipinjam' => 'bg-sky-50 text-sky-700',
            'dikembalikan' => 'bg-emerald-50 text-emerald-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    /**
     * Warna badge status denda.
     */
    public static function denda(?string $status): string
    {
        return match ($status) {
            'lunas' => 'bg-emerald-50 text-emerald-700',
            'belum_lunas' => 'bg-red-50 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    /**
     * Warna badge jaminan.
     */
    public static function jaminan(?string $status): string
    {
        return match ($status) {
            'diterima' => 'bg-emerald-50 text-emerald-700',
            'ditolak' => 'bg-red-50 text-red-700',
            default => 'bg-amber-50 text-amber-700',
        };
    }

    /**
     * Warna badge stok alat.
     */
    public static function alat(?string $status): string
    {
        return $status === 'tersedia'
            ? 'bg-emerald-50 text-emerald-700'
            : 'bg-red-50 text-red-700';
    }

    /**
     * Ubah label dalam bahasa Indonesia yang rapi.
     */
    public static function label(?string $value): string
    {
        if (! $value) {
            return '-';
        }

        return match ($value) {
            'belum_lunas' => 'Belum Lunas',
            'tidak_tersedia' => 'Tidak Tersedia',
            'jatuh_tempo' => 'Jatuh Tempo',
            default => ucfirst(str_replace('_', ' ', $value)),
        };
    }
}
