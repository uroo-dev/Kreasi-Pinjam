<?php

namespace Database\Factories;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alat>
 */
class AlatFactory extends Factory
{
    protected $model = Alat::class;

    /**
     * Isian kode_alat unik bergantian dengan angka urut.
     */
    public function configure(): static
    {
        return $this;
    }

    public function definition(): array
    {
        static $urut = 1;

        $stokTerakhir = Alat::query()->max('id');
        $nomor = max($urut, $stokTerakhir + 1);
        $urut = $nomor + 1;

        $stokTotal = fake()->numberBetween(5, 20);

        return [
            'kategori_id' => Kategori::factory(),
            'kode_alat' => 'ALT-'.$nomor,
            'nama_alat' => 'Alat Uji '.$nomor,
            'deskripsi' => fake()->sentence(),
            'stok_total' => $stokTotal,
            'stok_tersedia' => $stokTotal,
            'harga_sewa' => fake()->numberBetween(1, 10) * 10_000,
            'status' => 'tersedia',
        ];
    }
}
