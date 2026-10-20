<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data admin
        User::factory()->admin()->create([
            'nama' => 'uroo',
            'email' => 'uroo@gmail.com',
            'password' => Hash::make('password'),
            'no_hp' => '0882006533693',
        ]);

        // Data petugas
        User::factory()->petugas()->create([
            'nama' => 'petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('password'),
            'no_hp' => '0882006533694',
        ]);

        // Data peminjam beserta profilnya
        $peminjam = User::factory()->peminjam()->create([
            'nama' => 'peminjam',
            'email' => 'peminjam@gmail.com',
            'password' => Hash::make('password'),
            'no_hp' => '0882006533695',
        ]);

        Peminjam::create([
            'user_id' => $peminjam->id,
            'no_identitas' => 'IDN-0001',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Contoh No. 1, Bandung',
            'institusi' => 'Universitas Contoh',
            'pekerjaan' => 'Mahasiswa',
        ]);

        $this->call([KategoriAlatSeeder::class]);
    }
}
