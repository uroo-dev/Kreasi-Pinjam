<?php

namespace App\Services;

use App\Models\Alat;
use App\Models\Denda;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Support\Tanggungan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanService
{
    /**
     * Bangun kode peminjaman unik: PJ-YYMMDD-XXXX.
     */
    public static function kode(): string
    {
        do {
            $kode = 'PJ-'.now()->format('ymd').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Peminjaman::where('kode_peminjaman', $kode)->exists());

        return $kode;
    }

    /**
     * Ajukan peminjaman baru: cek tanggungan, validasi stok,
     * simpan peminjaman + detail dalam database transaction.
     *
     * @param  array<int, array{alat_id: int, jumlah: int}>  $items
     */
    public static function buatPengajuan(User $peminjam, array $data, array $items, string $ipAddress): Peminjaman
    {
        if (Tanggungan::tidakBolehMengajukan($peminjam)) {
            throw ValidationException::withMessages([
                'items' => 'Anda masih memiliki tanggungan (denda belum lunas atau terlambat mengembalikan). Selesaikan terlebih dahulu sebelum mengajukan peminjaman baru.',
            ]);
        }

        $tanggalPinjam = Carbon::parse($data['tanggal_pinjam']);
        $tanggalJatuhTempo = Carbon::parse($data['tanggal_jatuh_tempo']);

        if ($tanggalPinjam->lt($tanggalJatuhTempo->copy()->startOfDay()) === false
            && $tanggalPinjam->isAfter($tanggalJatuhTempo)) {
            throw ValidationException::withMessages([
                'tanggal_jatuh_tempo' => 'Tanggal jatuh tempo tidak boleh sebelum tanggal pinjam.',
            ]);
        }

        $durasi = $tanggalPinjam->startOfDay()->diffInDays($tanggalJatuhTempo->startOfDay());

        if ($durasi < config('peminjaman.durasi_minimal') || $durasi > config('peminjaman.durasi_maksimal')) {
            throw ValidationException::withMessages([
                'tanggal_jatuh_tempo' => 'Durasi peminjaman harus antara '.config('peminjaman.durasi_minimal')
                    .' sampai '.config('peminjaman.durasi_maksimal').' hari.',
            ]);
        }

        $peminjaman = DB::transaction(function () use ($peminjam, $data, $items, $durasi) {
            $totalHari = max(1, (int) $durasi);

            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => self::kode(),
                'user_id' => $peminjam->id,
                'tanggal_pinjam' => Carbon::parse($data['tanggal_pinjam']),
                'tanggal_jatuh_tempo' => Carbon::parse($data['tanggal_jatuh_tempo']),
                'status' => 'menunggu',
                'catatan' => $data['catatan'] ?? null,
            ]);

            foreach ($items as $item) {
                $alat = Alat::query()->lockForUpdate()->find($item['alat_id']);

                if (! $alat) {
                    throw ValidationException::withMessages(['items' => 'Ada alat yang tidak ditemukan.']);
                }

                $jumlah = (int) $item['jumlah'];

                if ($jumlah < 1) {
                    throw ValidationException::withMessages(['items' => 'Jumlah pinjam minimal 1 unit.']);
                }

                if ($jumlah > $alat->stok_tersedia || $alat->status !== 'tersedia') {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$alat->nama_alat} tersisa {$alat->stok_tersedia} unit.",
                    ]);
                }

                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $item['alat_id'],
                    'jumlah' => $jumlah,
                    'harga_sewa' => $alat->harga_sewa,
                    'subtotal' => DetailPeminjaman::calculateSubtotal($jumlah, $alat->harga_sewa, $totalHari),
                ]);
            }

            return $peminjaman;
        });

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'pengajuan',
            deskripsi: 'Pengajuan peminjaman '.$peminjaman->kode_peminjaman,
            userId: $peminjam->id,
            dataBaru: $peminjaman->toArray(),
            ipAddress: $ipAddress
        );

        return $peminjaman;
    }

    /**
     * Setujui pengajuan: wajib jaminan telah diterima, stok berkurang
     * di dalam database transaction agar tetap konsisten.
     */
    public static function setujui(Peminjaman $peminjaman, User $petugas, string $ipAddress): void
    {
        $jaminan = $peminjaman->jaminan;

        if (! $jaminan || $jaminan->status !== 'diterima') {
            throw ValidationException::withMessages([
                'peminjaman' => 'Jaminan atau pembayaran belum terverifikasi. Setujui jaminan terlebih dahulu sebelum menyetujui pengajuan.',
            ]);
        }

        DB::transaction(function () use ($peminjaman, $petugas) {
            $terkunci = Peminjaman::query()->lockForUpdate()->find($peminjaman->id);

            if ($terkunci->status !== 'menunggu') {
                throw ValidationException::withMessages([
                    'peminjaman' => 'Pengajuan sudah diproses sebelumnya.',
                ]);
            }

            $peminjaman->update([
                'status' => 'disetujui',
                'disetujui_oleh' => $petugas->id,
                'tanggal_disetujui' => now(),
            ]);

            foreach ($peminjaman->details as $detail) {
                Alat::query()->lockForUpdate()->find($detail->alat_id)?->updateStokTersedia();
            }
        });

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'setujui',
            deskripsi: 'Peminjaman '.$peminjaman->kode_peminjaman.' disetujui',
            userId: $petugas->id,
            dataBaru: $peminjaman->toArray(),
            ipAddress: $ipAddress
        );
    }

    /**
     * Tolak pengajuan dengan catatan.
     */
    public static function tolak(Peminjaman $peminjaman, User $petugas, string $catatan, string $ipAddress): void
    {
        $peminjaman->update([
            'status' => 'ditolak',
            'disetujui_oleh' => $petugas->id,
            'tanggal_disetujui' => now(),
            'catatan' => $catatan,
        ]);

        if ($peminjaman->jaminan && $peminjaman->jaminan->status === 'menunggu') {
            $peminjaman->jaminan->update(['status' => 'ditolak']);
        }

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'tolak',
            deskripsi: 'Peminjaman '.$peminjaman->kode_peminjaman.' ditolak: '.$catatan,
            userId: $petugas->id,
            dataBaru: $peminjaman->toArray(),
            ipAddress: $ipAddress
        );
    }

    /**
     * Tandai alat telah diambil peminjam (status dipinjam).
     */
    public static function serahkan(Peminjaman $peminjaman, User $petugas, string $ipAddress): void
    {
        if ($peminjaman->status !== 'disetujui') {
            throw ValidationException::withMessages([
                'peminjaman' => 'Peminjaman harus berstatus disetujui sebelum alat diserahkan.',
            ]);
        }

        $peminjaman->update(['status' => 'dipinjam']);

        LogAktivitas::log(
            modul: 'peminjaman',
            aksi: 'serahkan',
            deskripsi: 'Alat '.$peminjaman->kode_peminjaman.' telah diserahkan ke peminjam',
            userId: $petugas->id,
            dataBaru: $peminjaman->toArray(),
            ipAddress: $ipAddress
        );
    }

    /**
     * Proses pengembalian seluruh alat pada satu peminjaman:
     * hitung hari terlambat, buat denda (terlambat/rusak/hilang),
     * perbarui stok alat, ubah status menjadi dikembalikan.
     *
     * @param  array<int, array{alat_id: int, kondisi_alat: string, catatan?: string}>  $items
     */
    public static function prosesPengembalian(
        Peminjaman $peminjaman,
        User $petugas,
        array $items,
        ?string $tanggalKembali = null,
        ?string $catatan = null,
        string $ipAddress = ''
    ): float {
        $tanggalKembali = Carbon::parse($tanggalKembali ?? now());

        return DB::transaction(function () use ($peminjaman, $petugas, $items, $tanggalKembali, $catatan, $ipAddress) {
            $jatuhTempo = $peminjaman->tanggal_jatuh_tempo->copy()->startOfDay();

            $hariTerlambat = $tanggalKembali->startOfDay()->isAfter($jatuhTempo)
                ? $jatuhTempo->diffInDays($tanggalKembali->startOfDay())
                : 0;

            $dendaPerHari = (int) config('peminjaman.denda_keterlambatan_per_hari');
            $dendaRusakPersen = (float) config('peminjaman.denda_kerusakan_persen');
            $dendaKehilanganKelipatan = (int) config('peminjaman.denda_kehilangan_kelipatan');

            $totalDendaTerlambat = 0.0;
            $totalDendaKerusakan = 0.0;
            $totalDendaKehilangan = 0.0;

            foreach ($items as $item) {
                $detail = $peminjaman->details->firstWhere('alat_id', (int) $item['alat_id']);

                if (! $detail) {
                    throw ValidationException::withMessages([
                        'items' => 'Alat tidak terdaftar pada peminjaman ini.',
                    ]);
                }

                $kondisi = $item['kondisi_alat'];

                if (! in_array($kondisi, ['baik', 'rusak', 'hilang'])) {
                    throw ValidationException::withMessages([
                        'items' => 'Kondisi alat harus diisi dengan baik, rusak, atau hilang.',
                    ]);
                }

                $dendaKeterlambatan = $hariTerlambat > 0
                    ? $hariTerlambat * $dendaPerHari * $detail->jumlah
                    : 0.0;

                $dendaKondisi = match ($kondisi) {
                    'rusak' => $detail->harga_sewa * ($dendaRusakPersen / 100) * $detail->jumlah,
                    'hilang' => $detail->harga_sewa * $dendaKehilanganKelipatan * $detail->jumlah,
                    default => 0.0,
                };

                $dendaBaris = round($dendaKeterlambatan + $dendaKondisi, 2);

                Pengembalian::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $detail->alat_id,
                    'jumlah_kembali' => $detail->jumlah,
                    'tanggal_kembali' => $tanggalKembali,
                    'hari_terlambat' => (int) $hariTerlambat,
                    'kondisi_alat' => $kondisi,
                    'total_denda' => $dendaBaris,
                    'catatan' => $item['catatan'] ?? $catatan,
                    'diproses_oleh' => $petugas->id,
                ]);

                $totalDendaTerlambat += $dendaKeterlambatan;
                $totalDendaKerusakan += $kondisi === 'rusak' ? $dendaKondisi : 0;
                $totalDendaKehilangan += $kondisi === 'hilang' ? $dendaKondisi : 0;
            }

            // Stok: alat baik kembali penuh, rusak/hilang menyusutkan stok total.
            foreach ($peminjaman->details as $detail) {
                $kondisi = collect($items)->firstWhere('alat_id', $detail->alat_id)['kondisi_alat'];
                $alat = Alat::query()->lockForUpdate()->find($detail->alat_id);

                if (in_array($kondisi, ['rusak', 'hilang'])) {
                    $alat->stok_total = max(0, $alat->stok_total - $detail->jumlah);
                    $alat->save();
                }
            }

            $peminjaman->update(['status' => 'dikembalikan']);

            foreach ($peminjaman->details->pluck('alat_id')->unique() as $alatId) {
                Alat::find($alatId)?->updateStokTersedia();
            }

            $totalDenda = round($totalDendaTerlambat + $totalDendaKerusakan + $totalDendaKehilangan, 2);

            if ($totalDendaTerlambat > 0) {
                self::catatDenda($peminjaman, 'terlambat', $totalDendaTerlambat, "Keterlambatan {$hariTerlambat} hari pada {$peminjaman->kode_peminjaman}");
            }

            if ($totalDendaKerusakan > 0) {
                self::catatDenda($peminjaman, 'rusak', $totalDendaKerusakan, 'Kerusakan alat pada '.$peminjaman->kode_peminjaman);
            }

            if ($totalDendaKehilangan > 0) {
                self::catatDenda($peminjaman, 'hilang', $totalDendaKehilangan, 'Alat hilang pada '.$peminjaman->kode_peminjaman);
            }

            LogAktivitas::log(
                modul: 'pengembalian',
                aksi: 'proses',
                deskripsi: 'Pengembalian '.$peminjaman->kode_peminjaman
                    ." ({$peminjaman->details->count()} jenis alat), denda total Rp"
                    .number_format($totalDenda, 0, ',', '.'),
                userId: $petugas->id,
                dataBaru: $peminjaman->toArray(),
                ipAddress: $ipAddress
            );

            return $totalDenda;
        });
    }

    private static function catatDenda(Peminjaman $peminjaman, string $jenis, float $nominal, string $keterangan): void
    {
        Denda::create([
            'user_id' => $peminjaman->user_id,
            'pengembalian_id' => Pengembalian::query()->where('peminjaman_id', $peminjaman->id)->first()?->id,
            'jenis_denda' => $jenis,
            'nominal' => round($nominal, 2),
            'status' => 'belum_lunas',
            'keterangan' => $keterangan,
        ]);
    }
}
