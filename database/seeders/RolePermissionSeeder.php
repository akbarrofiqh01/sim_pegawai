<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'lihat pegawai',
            'tombol tambah pegawai',
            'tombol ubah pegawai',
            'tombol hapus pegawai',
            'tombol roles pegawai',
            'akses dashboard kepegawaian',
            'lihat data jabatan',
            'tombol tambah jabatan',
            'tombol ubah jabatan',
            'tombol hapus jabatan',
            'tombol login pegawai',
            'tombol login kembali pegawai',
            'lihat role user',
            'tombol tambah role user',
            'tombol ubah role user',
            'tombol hapus role user',
            'lihat permissions user',
            'tombol tambah permissions user',
            'tombol ubah permissions user',
            'tombol hapus permissions user',
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
            'lihat menu kepegawaian',
            'lihat menu pengaturan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'code_permissions' => Str::random(60)]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web', 'code_role'    => Str::random(60)]);
        $superAdmin->syncPermissions(Permission::all());

        $roles = [
            'admin' => [
                'akses dashboard kepegawaian',
                'lihat pegawai',
                'tombol tambah pegawai',
                'tombol ubah pegawai',
                'tombol hapus pegawai',
                'tombol roles pegawai',
                'lihat data jabatan',
                'tombol tambah jabatan',
                'tombol ubah jabatan',
                'tombol hapus jabatan',
                'tombol login pegawai',
                'tombol login kembali pegawai',
                'lihat role user',
                'tombol tambah role user',
                'tombol ubah role user',
                'tombol hapus role user',
                'lihat permissions user',
                'tombol tambah permissions user',
                'tombol ubah permissions user',
                'tombol hapus permissions user',
            ]
        ];

        foreach ($roles as $role => $perms) {
            $r = Role::firstOrCreate(['name' => $role, 'code_role' => Str::random(60)]);
            $r->syncPermissions($perms);
        }
    }
}
