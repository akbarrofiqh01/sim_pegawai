<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('password'),
            'code_user' => Str::random(60),
        ]);
        $superAdmin->assignRole('superadmin');

        $pegawai = Pegawai::limit(3)->get();
        foreach ($pegawai as $run) {
            $pegawai = User::create([
                'name'        => $run->fullname,
                'email'       => $run->email,
                'password'    => Hash::make('password'),
                'code_user' => Str::random(60),
                'pegawai_id'  => $run->id,
            ]);
        }
    }
}
