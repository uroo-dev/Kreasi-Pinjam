<?php

namespace App\Support;

class Menu
{
    /**
     * Daftar menu sidebar berdasarkan role.
     */
    public static function untuk(?string $role): array
    {
        return match ($role) {
            'admin' => self::admin(),
            'petugas' => self::petugas(),
            'peminjam' => self::peminjam(),
            default => [],
        };
    }

    public static function labelRole(?string $role): string
    {
        return match ($role) {
            'admin' => 'ADMIN',
            'petugas' => 'PETUGAS',
            'peminjam' => 'PEMINJAM',
            default => 'AKUN',
        };
    }

    private static function admin(): array
    {
        return [
            [
                'grup' => 'Menu Utama',
                'items' => [
                    ['route' => 'dashboard', 'ikon' => 'layout-dashboard', 'label' => 'Dashboard', 'aktif' => 'dashboard'],
                ],
            ],
            [
                'grup' => 'Master Data',
                'items' => [
                    ['route' => 'kategori/get', 'ikon' => 'layout-grid', 'label' => 'Kategori', 'aktif' => 'kategori/*'],
                    ['route' => 'alat/get', 'ikon' => 'package', 'label' => 'Alat', 'aktif' => 'alat/*'],
                    ['route' => 'user/get', 'ikon' => 'users', 'label' => 'Pengguna', 'aktif' => 'user/*'],
                ],
            ],
            [
                'grup' => 'Laporan',
                'items' => [
                    ['route' => 'laporan/peminjaman', 'ikon' => 'shopping-bag', 'label' => 'Peminjaman', 'aktif' => 'laporan/peminjaman'],
                    ['route' => 'laporan/pengembalian', 'ikon' => 'undo-2', 'label' => 'Pengembalian', 'aktif' => 'laporan/pengembalian'],
                    ['route' => 'laporan/denda', 'ikon' => 'banknote', 'label' => 'Denda', 'aktif' => 'laporan/denda'],
                ],
            ],
        ];
    }

    private static function petugas(): array
    {
        return [
            [
                'grup' => 'Menu Utama',
                'items' => [
                    ['route' => 'petugas/dashboard', 'ikon' => 'layout-dashboard', 'label' => 'Dashboard', 'aktif' => 'petugas/dashboard'],
                ],
            ],
            [
                'grup' => 'Transaksi',
                'items' => [
                    ['route' => 'petugas/persetujuan/get', 'ikon' => 'clipboard-check', 'label' => 'Persetujuan', 'aktif' => 'petugas/persetujuan/*'],
                    ['route' => 'petugas/pengembalian/get', 'ikon' => 'undo-2', 'label' => 'Pengembalian', 'aktif' => 'petugas/pengembalian/*'],
                    ['route' => 'petugas/denda/get', 'ikon' => 'banknote', 'label' => 'Denda', 'aktif' => 'petugas/denda/*'],
                ],
            ],
            [
                'grup' => 'Laporan',
                'items' => [
                    ['route' => 'laporan/peminjaman', 'ikon' => 'shopping-bag', 'label' => 'Peminjaman', 'aktif' => 'laporan/peminjaman'],
                    ['route' => 'laporan/pengembalian', 'ikon' => 'undo-2', 'label' => 'Pengembalian', 'aktif' => 'laporan/pengembalian'],
                    ['route' => 'laporan/denda', 'ikon' => 'banknote', 'label' => 'Denda', 'aktif' => 'laporan/denda'],
                ],
            ],
        ];
    }

    private static function peminjam(): array
    {
        return [
            [
                'grup' => 'Menu Utama',
                'items' => [
                    ['route' => 'peminjam/dashboard', 'ikon' => 'layout-dashboard', 'label' => 'Dashboard', 'aktif' => 'peminjam/dashboard'],
                ],
            ],
            [
                'grup' => 'Peminjaman',
                'items' => [
                    ['route' => 'peminjam/katalog/get', 'ikon' => 'store', 'label' => 'Katalog Alat', 'aktif' => 'peminjam/katalog/*'],
                    ['route' => 'peminjam/riwayat/get', 'ikon' => 'history', 'label' => 'Riwayat', 'aktif' => 'peminjam/riwayat/*'],
                ],
            ],
            [
                'grup' => 'Tanggungan',
                'items' => [
                    ['route' => 'peminjam/denda/get', 'ikon' => 'banknote', 'label' => 'Denda Saya', 'aktif' => 'peminjam/denda/tanggungan'],
                ],
            ],
        ];
    }
}
