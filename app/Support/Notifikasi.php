<?php

namespace App\Support;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Notifikasi
{
    /**
     * Kumpulkan daftar notifikasi sesuai role pengguna.
     *
     * @return array{jumlah: int, items: Collection}
     */
    public static function untuk($user = null): array
    {
        if (! $user) {
            $user = Auth::user();
        }

        if (! $user) {
            return ['jumlah' => 0, 'items' => collect()];
        }

        $items = match ($user->role) {
            'admin' => self::untukAdmin(),
            'petugas' => self::untukPetugas(),
            'peminjam' => self::untukPeminjam(),
            default => collect(),
        };

        return [
            'jumlah' => $items->count(),
            'items' => $items,
        ];
    }

    private static function untukAdmin(): Collection
    {
        $pengajuan = self::pengajuanMenunggu(3);

        $stokMenipis = Peminjaman::query()
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '<', now())
            ->with('user')
            ->limit(3)
            ->get()->toBase();

        $items = $pengajuan->map(function (Peminjaman $p) {
            return [
                'ikon' => 'clipboard-list',
                'warna' => 'bg-amber-50',
                'warna_ikon' => 'text-amber-600',
                'judul' => 'Pengajuan baru '.$p->kode_peminjaman,
                'ket' => ($p->user->nama ?? '-').' mengajukan peminjaman',
                'route' => route('petugas/persetujuan/get'),
            ];
        });

        return $items->merge($stokMenipis->map(function (Peminjaman $p) {
            return [
                'ikon' => 'alert-triangle',
                'warna' => 'bg-red-50',
                'warna_ikon' => 'text-red-500',
                'judul' => 'Melewati jatuh tempo',
                'ket' => ($p->user->nama ?? '-').' - '.$p->kode_peminjaman,
                'route' => route('petugas/pengembalian/get'),
            ];
        }));
    }

    private static function untukPetugas(): Collection
    {
        $items = self::pengajuanMenunggu(4)->map(function (Peminjaman $p) {
            return [
                'ikon' => 'clipboard-check',
                'warna' => 'bg-amber-50',
                'warna_ikon' => 'text-amber-600',
                'judul' => 'Perlu persetujuan: '.$p->kode_peminjaman,
                'ket' => ($p->user->nama ?? '-').' menunggu verifikasi jaminan',
                'route' => route('petugas/persetujuan/get'),
            ];
        });

        $dendaBelumLunas = Denda::query()
            ->where('status', 'belum_lunas')
            ->with('user')
            ->limit(3)
            ->get()->toBase()->map(function (Denda $d) {
                return [
                    'ikon' => 'banknote',
                    'warna' => 'bg-sky-50',
                    'warna_ikon' => 'text-sky-600',
                    'judul' => 'Denda belum lunas',
                    'ket' => ($d->user->nama ?? '-').' sebesar Rp'.number_format($d->nominal - $d->dibayar, 0, ',', '.'),
                    'route' => route('petugas/denda/get'),
                ];
            });

        return $items->merge($dendaBelumLunas);
    }

    private static function untukPeminjam(): Collection
    {
        $userId = Auth::id();

        $items = Peminjaman::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->with('details.alat')
            ->limit(4)
            ->get()->toBase()->map(function (Peminjaman $p) {
                $namaAlat = $p->details->first()?->alat?->nama_alat ?? 'alat';
                $jatuhTempo = $p->tanggal_jatuh_tempo?->format('d M Y');

                return [
                    'ikon' => 'hand-coins',
                    'warna' => 'bg-emerald-50',
                    'warna_ikon' => 'text-emerald-600',
                    'judul' => $p->status === 'disetujui'
                        ? 'Peminjaman disetujui'
                        : 'Alat sedang dipinjam',
                    'ket' => $namaAlat.' - kembalikan paling lambat '.$jatuhTempo,
                    'route' => route('peminjam/riwayat/get'),
                ];
            });

        return $items->merge(
            Denda::query()
                ->where('status', 'belum_lunas')
                ->limit(3)
                ->get()->toBase()->map(function (Denda $d) {
                    return [
                        'ikon' => 'banknote',
                        'warna' => 'bg-red-50',
                        'warna_ikon' => 'text-red-500',
                        'judul' => 'Denda belum lunas',
                        'ket' => 'Total tanggungan Rp'.number_format($d->nominal - $d->dibayar, 0, ',', '.'),
                        'route' => route('peminjam/denda/get'),
                    ];
                })
        );
    }

    /**
     * Kembalikan daftar pengajuan peminjaman yang masih menunggu.
     */
    private static function pengajuanMenunggu(int $batas): Collection
    {
        return Peminjaman::query()
            ->where('status', 'menunggu')
            ->with('user')
            ->latest()
            ->limit($batas)
            ->get()->toBase();
    }
}
