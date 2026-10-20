<?php

namespace Tests\Feature\Admin;

use App\Models\Alat;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_laporan_peminjaman_menampilkan_data_periode(): void
    {
        $admin = User::factory()->admin()->create();
        $peminjam = User::factory()->peminjam()->create();
        $alat = Alat::factory()->create();

        Peminjaman::query()->create([
            'kode_peminjaman' => 'PJ-UJI-01',
            'user_id' => $peminjam->id,
            'tanggal_pinjam' => now()->subDays(2)->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(2)->toDateString(),
            'status' => 'dikembalikan',
        ]);

        $this->actingAs($admin)
            ->get(route('laporan/peminjaman', [
                'awal' => now()->subDays(7)->toDateString(),
                'akhir' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('PJ-UJI-01', false);
    }

    public function test_laporan_denda_menghitung_nilai_belum_lunas()
    {
        $admin = User::factory()->admin()->create();
        $peminjam = User::factory()->peminjam()->create();

        Denda::query()->create([
            'user_id' => $peminjam->id,
            'jenis_denda' => 'terlambat',
            'nominal' => 12000,
            'dibayar' => 4000,
            'status' => 'belum_lunas',
            'keterangan' => 'Keterlambatan 2 hari pada PJ-UJI-01',
        ]);

        $this->actingAs($admin)->get(route('laporan/denda'))
            ->assertOk()
            ->assertSee('Rp'.number_format(12000 - 4000, 0, ',', '.'), false);
    }

    public function test_petugas_tidak_akan_melihat_laporan()
    {
        $petugas = User::factory()->petugas()->create();

        $this->actingAs($petugas)->get(route('laporan/denda'))->assertForbidden();
    }
}
