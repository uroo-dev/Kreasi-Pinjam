<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\PeminjamanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengajuan_peminjam_berstatus_menunggu_dan_tanggal_tercatat(): void
    {
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create(['stok_total' => 5, 'stok_tersedia' => 5]);

        $response = $this->actingAs($peminjam)->post(route('peminjam/katalog/ajukan', $alat), [
            'jumlah' => 2,
            'tanggal_pinjam' => now()->addDay()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(4)->toDateString(),
            'catatan' => 'Kebutuhan praktikum',
        ]);

        $response->assertRedirect(route('peminjam/riwayat/get'));
        $response->assertSessionHas('success');

        $peminjaman = Peminjaman::where('user_id', $peminjam->id)->first();
        $this->assertNotNull($peminjaman);
        $this->assertSame('menunggu', $peminjaman->status);
        $this->assertSame(2, $peminjaman->details()->count() === 0 ? 0 : $peminjaman->details()->first()->jumlah);
        $this->assertSame($alat->id, $peminjaman->details()->first()->alat_id);
        $nova = $alat->fresh();
        $this->assertSame(5, $nova->stok_tersedia);
    }

    public function test_pengajuan_dengan_jumlah_lebih_dari_stok_ditolak(): void
    {
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create(['stok_total' => 2, 'stok_tersedia' => 2]);

        $this->actingAs($peminjam)->post(route('peminjam/katalog/ajukan', $alat), [
            'jumlah' => 3,
            'tanggal_pinjam' => now()->addDay()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(4)->toDateString(),
        ])->assertSessionHasErrors(['items']);
    }

    public function test_pengajuan_durasi_belum_sesuai_konfigurasi_ditolak(): void
    {
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create();

        $this->actingAs($peminjam)->post(route('peminjam/katalog/ajukan', $alat), [
            'jumlah' => 1,
            'tanggal_pinjam' => now()->addDay()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(30)->toDateString(),
        ])->assertSessionHasErrors('tanggal_jatuh_tempo');
    }

    public function test_setujui_tanpa_jaminan_diterima_menghasilkan_error(): void
    {
        $petugas = User::factory()->petugas()->create();
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create(['stok_total' => 4, 'stok_tersedia' => 4]);

        $peminjaman = PeminjamanService::buatPengajuan(
            $peminjam,
            [
                'tanggal_pinjam' => now()->addDay()->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(3)->toDateString(),
            ],
            [['alat_id' => $alat->id, 'jumlah' => 1]],
            '127.0.0.1'
        );

        $this->actingAs($petugas)->post(route('petugas/peminjaman/setujui', $peminjaman))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertSame('menunggu', $peminjaman->fresh()->status);
        $this->assertSame(4, $alat->fresh()->stok_tersedia);
    }

    public function test_penghapusan_alat_memakai_soft_delete_dan_stok_tetap(): void
    {
        $alat = Alat::factory()->create(['stok_total' => 7]);
        $alat->delete();

        $this->assertSoftDeleted('alat', ['id' => $alat->id]);
    }
}
