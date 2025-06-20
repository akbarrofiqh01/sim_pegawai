<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawais = [
            [
                'nip'            => '198101011999031001',
                'fullname'       => 'Budi Santoso',
                'jabatan_id'     => 1,
                'status_nikah'   => 'K1',
                'jenis_kelamin'  => 'L',
                'tempat_lahir'   => 'Jakarta',
                'tanggal_lahir'  => '1981-01-01',
                'agama'          => 'Islam',
                'email'          => 'budi@example.com',
                'no_telp'        => '081234567890',
                'alamat'         => 'Jl. Merdeka No.1',
                'code_pegawai'   => Str::random(60),
            ],
            [
                'nip'            => '198205101998032002',
                'fullname'       => 'Siti Aminah',
                'jabatan_id'     => 2,
                'status_nikah'   => 'K2',
                'jenis_kelamin'  => 'P',
                'tempat_lahir'   => 'Bandung',
                'tanggal_lahir'  => '1982-05-10',
                'agama'          => 'Islam',
                'email'          => 'siti@example.com',
                'no_telp'        => '082345678901',
                'alamat'         => 'Jl. Asia Afrika No.2',
                'code_pegawai'   => Str::random(60),
            ],
            [
                'nip'            => '197512251997011003',
                'fullname'       => 'Agus Haryanto',
                'jabatan_id'     => 3,
                'status_nikah'   => 'TK',
                'jenis_kelamin'  => 'L',
                'tempat_lahir'   => 'Surabaya',
                'tanggal_lahir'  => '1975-12-25',
                'agama'          => 'Kristen',
                'email'          => 'agus@example.com',
                'no_telp'        => '083356789012',
                'alamat'         => 'Jl. Darmo No.3',
                'code_pegawai'   => Str::random(60),
            ],
        ];

        foreach ($pegawais as $data) {
            Pegawai::create($data);
        }
    }
}
