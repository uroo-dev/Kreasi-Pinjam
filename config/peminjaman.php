<?php

return [
    // Batas durasi peminjaman (hari) saat mengajukan
    'durasi_minimal' => 1,
    'durasi_maksimal' => 14,

    // Denda keterlambatan (per hari, per unit alat)
    'denda_keterlambatan_per_hari' => env('DENDA_KETERLAMBATAN_PER_HARI', 5000),

    // Denda kerusakan: persentase harga sewa yang dikalikan dengan jumlah unit rusak
    'denda_kerusakan_persen' => env('DENDA_KERUSAKAN_PERSEN', 50),

    // Denda kehilangan: kelipatan harga sewa per unit alat yang hilang
    'denda_kehilangan_kelipatan' => env('DENDA_KEHILANGAN_KELIPATAN', 10),
];
