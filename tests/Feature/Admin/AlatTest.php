<?php

namespace Tests\Feature\Admin;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlatTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_menambah_alat_dengan_stok_otomatis(): void
    {
        $admin = User::factory()->admin()->create();
        $kategori = Kategori::factory()->create();

        $response = $this->actingAs($admin)->post(route('alat/store'), [
            'kode_alat' => 'ALT-777',
            'nama_alat' => 'Proyektor Epson EB-X500',
            'kategori_id' => $kategori->id,
            'stok_total' => 10,
            'harga_sewa' => 25000,
            'deskripsi' => 'Proyektor untuk ruang seminar',
        ]);

        $response->assertRedirect(route('alat/get'));
        $response->assertSessionHas('success');

        $alat = Alat::where('nama_alat', 'Proyektor Epson EB-X500')->first();
        $this->assertNotNull($alat);
        $this->assertSame(10, $alat->stok_tersedia);
        $this->assertSame('tersedia', $alat->status);
        $this->assertStringStartsWith('ALT-', $alat->kode_alat);
    }

    public function test_alat_dengan_kode_duplikat_ditolak(): void
    {
        $admin = User::factory()->admin()->create();
        $kategori = Kategori::factory()->create();
        Alat::factory()->create(['kode_alat' => 'ALT-99', 'kategori_id' => $kategori->id]);

        $response = $this->actingAs($admin)->post(route('alat/store'), [
            'kode_alat' => 'ALT-99',
            'nama_alat' => 'Duplikat',
            'kategori_id' => $kategori->id,
            'stok_total' => 5,
            'harga_sewa' => 10000,
        ]);

        $response->assertSessionHasErrors(['kode_alat']);
        $this->assertDatabaseMissing('alat', ['nama_alat' => 'Duplikat']);
    }

    public function test_penghapusan_alat_merupakan_soft_delete(): void
    {
        $admin = User::factory()->admin()->create();
        $alat = Alat::factory()->create();

        $this->actingAs($admin)->delete(route('alat/destroy', $alat))->assertRedirect();

        $this->assertSoftDeleted('alat', ['id' => $alat->id]);
    }

    public function test_petugas_tidak_bisa_mengubah_alat(): void
    {
        $petugas = User::factory()->petugas()->create();
        $alat = Alat::factory()->create();

        $this->actingAs($petugas)
            ->put(route('alat/update', $alat), ['nama_alat' => 'Diretas'])
            ->assertForbidden();
    }
}
