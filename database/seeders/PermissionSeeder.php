<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'lihat cuti',
            'tombol tambah cuti',
            'tombol edit cuti',
            'tombol hapus cuti',
            'lihat pengajuan cuti',
            'tombol tambah pengajuan cuti',
            'tombol edit pengajuan cuti',
            'tombol hapus pengajuan cuti',
            'lihat persetujuan cuti',
            'tombol approved persetujuan cuti',
            'tombol rejected persetujuan cuti',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'code_permissions' => Str::random(60)]);
        }
    }
}
