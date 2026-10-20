<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\PeminjamanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengembalianTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengembalian_terlambat_melipatgandakan_denda_dengan_benar(): void
    {
        // Pengajuan tanggal -8 sampai -4 → jatuh tempo 4 hari lampau; kembali hari ini (4 hari terlambat).
        $petugas = User::factory()->petugas()->create();
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create([
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'harga_sewa' => 10000,
        ]);

        $peminjaman = PeminjamanService::buatPengajuan(
            $peminjam,
            [
                'tanggal_pinjam' => now()->subDays(8)->toDateString(),
                'tanggal_jatuh_tempo' => now()->subDays(4)->toDateString(),
            ],
            [['alat_id' => $alat->id, 'jumlah' => 2]],
            '127.0.0.1'
        );

        $totalDenda = PeminjamanService::prosesPengembalian(
            $peminjaman,
            $petugas,
            [['alat_id' => $alat->id, 'kondisi_alat' => 'baik', 'catatan' => null]],
            now()->toDateString(),
            null,
            '127.0.0.1'
        );

        // 4 hari * 5000/hari * 2 unit = 40.000
        $this->assertSame(40_000.0, $totalDenda);
        $this->assertSame('dikembalikan', $peminjaman->fresh()->status);
        $this->assertSame(5, $alat->fresh()->stok_tersedia);

        $denda = Denda::query()->where('user_id', $peminjam->id)->where('jenis_denda', 'terlambat')->first();
        $this->assertNotNull($denda);
        $this->assertSame(40_000.0, (float) $denda->nominal);
        $this->assertSame('belum_lunas', $denda->status);
    }

    public function test_unit_rusak_mempotong_stok_total_dan_mencatat_denda_kerusakan(): void
    {
        $petugas = User::factory()->petugas()->create();
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create([
            'stok_total' => 6,
            'stok_tersedia' => 6,
            'harga_sewa' => 10000,
        ]);

        $peminjaman = PeminjamanService::buatPengajuan(
            $peminjam,
            [
                'tanggal_pinjam' => now()->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(3)->toDateString(),
            ],
            [['alat_id' => $alat->id, 'jumlah' => 2]],
            '127.0.0.1'
        );

        $totalDenda = PeminjamanService::prosesPengembalian(
            $peminjaman,
            $petugas,
            [['alat_id' => $alat->id, 'kondisi_alat' => 'rusak', 'catatan' => 'Lampu mati satu']],
            now()->addDay()->toDateString(),
            null,
            '127.0.0.1'
        );

        // rusak: 50% * 10.000 * 2 = 10.000; tidak terlambat → tanpa denda keterlambatan
        $this->assertSame(10_000.0, $totalDenda);
        // stok total berkurang 2 unit: 6 → 4
        $this->assertSame(4, $alat->fresh()->stok_total);
        $this->assertSame(4, (int) $alat->fresh()->stok_tersedia);

        $denda = Denda::query()->where('user_id', $peminjam->id)->where('jenis_denda', 'rusak')->first();
        $this->assertNotNull($denda);
    }

    public function test_pembayaran_denda_sebagian_lalu_lunas(): void
    {
        $petugas = User::factory()->petugas()->create();
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create();

        $pengajuan = PeminjamanService::buatPengajuan($peminjam, [
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(2)->toDateString(),
        ], [['alat_id' => $alat->id, 'jumlah' => 1]], '127.0.0.1');

        $pengajuan->jaminan()->create([
            'jenis' => 'jaminan',
            'keterangan' => 'KTP',
            'status' => 'diterima',
        ]);

        PeminjamanService::setujui($pengajuan, $petugas, '127.0.0.1');
        PeminjamanService::serahkan($pengajuan, $petugas, '127.0.0.1');

        PeminjamanService::prosesPengembalian(
            $pengajuan,
            $petugas,
            [['alat_id' => $alat->id, 'kondisi_alat' => 'baik', 'catatan' => null]],
            now()->addDays(4)->toDateString(),
            null,
            '127.0.0.1'
        );

        $denda = Denda::where('user_id', $peminjam->id)->first();
        $this->assertSame(10_000.0, (float) $denda->nominal);

        $peminjamDenda = Denda::find($denda->id);
        $this->actingAs($petugas)->post(route('petugas/denda/bayar', $denda), ['jumlah' => 4000])->assertRedirect();
        $denda->refresh();
        $this->assertSame(4000.0, (float) $denda->dibayar);
        $this->assertSame('belum_lunas', $denda->status);

        $this->actingAs($petugas)->post(route('petugas/denda/bayar', $denda), ['jumlah' => 6000 + 0.0])->assertRedirect();
        $denda->refresh();
        $this->assertSame('lunas', $denda->status);
        $this->assertSame(10_000.0, (float) $denda->dibayar);

        $zaman = $peminjamDenda->fresh();
        $this->assertSame('lunas', $zaman->status);
        $this->assertSame('dikembalikan', Peminjaman::find($pengajuan->id)->status);
    }
}
