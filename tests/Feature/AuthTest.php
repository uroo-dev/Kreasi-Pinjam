<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_dengan_password_salah_ditolak(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->from(route('login'))->post(route('login_proses'), [
            'nama' => $admin->nama,
            'password' => 'salah-banget',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_login_admin_berhasil_lalu_redirect_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->post(route('login_proses'), [
            'nama' => $admin->nama,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_peminjam_mengakses_admin_ditolak(): void
    {
        $peminjam = User::factory()->peminjam()->create();

        $this->actingAs($peminjam)
            ->get('/admin/kategori')
            ->assertForbidden();
    }

    public function test_tamu_dialihkan_ke_halaman_login(): void
    {
        $this->get('/admin/kategori')->assertRedirect(route('login'));
    }
}
