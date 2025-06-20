<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'nama_jabatan' => 'Kepala Sekolah',
                'gaji_pokok'   => 10000000,
                'code_jabatan' => Str::random(60),
            ],
            [
                'nama_jabatan' => 'Wakil Kepala Sekolah',
                'gaji_pokok'   => 8500000,
                'code_jabatan' => Str::random(60),
            ],
            [
                'nama_jabatan' => 'Guru Senior',
                'gaji_pokok'   => 7500000,
                'code_jabatan' => Str::random(60),
            ],
            [
                'nama_jabatan' => 'Guru Junior',
                'gaji_pokok'   => 5500000,
                'code_jabatan' => Str::random(60),
            ],
            [
                'nama_jabatan' => 'Staff TU',
                'gaji_pokok'   => 4000000,
                'code_jabatan' => Str::random(60),
            ],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }
    }
}
