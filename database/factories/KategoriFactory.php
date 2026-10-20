<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        static $urut = 1;

        $ada = [
            'Elektronik',
            'Jaringan',
            'Olahraga',
            'Perabot',
            'Alat Kebersihan',
        ];

        $nama = $ada[($urut - 1) % count($ada)].($urut > count($ada) ? ' '.$urut : '');
        $urut++;

        return [
            'nama_kategori' => $nama,
        ];
    }
}
