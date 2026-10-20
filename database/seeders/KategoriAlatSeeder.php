<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriAlatSeeder extends Seeder
{
    /**
     * Data contoh kategori dan alat untuk demo aplikasi.
     */
    public function run(): void
    {
        $kategoris = [
            'Elektronik' => ['Proyektor Epson EB-X500', 'Canon EOS 700D Kit 18-55mm', 'Canon EF 50mm f/1.8 STM'],
            'Jaringan' => ['Mikrotik Router RB941', 'Switch TP-Link 8 Port', 'Wireless TP-Link WR840N'],
            'Olahraga' => ['Bola Voli Mikasa MVA200', 'Raket Yonex Astrox 01', 'Matras Yoga Premium 6mm'],
        ];

        foreach ($kategoris as $namaKategori => $alats) {
            $kategori = Kategori::factory()->create([
                'nama_kategori' => $namaKategori,
            ]);

            foreach ($alats as $namaAlat) {
                Alat::factory()->create([
                    'nama_alat' => $namaAlat,
                    'kategori_id' => $kategori->id,
                ]);
            }
        }
    }
}
