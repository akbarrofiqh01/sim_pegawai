<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lihat permissions user')->only('index');
        $this->middleware('permission:tombol tambah permissions user')->only('create');
        $this->middleware('permission:tombol ubah permissions user')->only('edit');
        $this->middleware('permission:tombol hapus permissions user')->only('destroy');
    }
    public function index()
    {
        $permission = Permission::all()->sortByDesc('id');
        return view(
            'page.pengaturan.list_permissions',
            [
                'dataPermissions'       => $permission
            ]
        );
    }
    public function create()
    {
        return view('page.pengaturan.modal.tambah_permissions');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => ['required', 'unique:permissions', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama permissions wajib diisi !!!',
            'name.unique'        => 'Bagian nama permissions harus unique !!!',
        ]);

        $nwPermissions = new Permission();
        $nwPermissions->name = $request->name;
        $nwPermissions->code_permissions = Str::random(60);
        $nwPermissions->saveOrFail();
        return response()->json([
            'message'           => 'Permissions berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function edit($codePermissions)
    {
        $permissions = Permission::where('code_permissions', $codePermissions)->firstOrFail();
        return view(
            'page.pengaturan.modal.edit_permissions',
            ['dataPermissions' => $permissions]
        );
    }

    public function update(Request $request, $permissionsCode)
    {
        $validated = $request->validate([
            'name'                 => ['required', 'unique:permissions', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama permissions wajib diisi !!!',
            'name.unique'        => 'Bagian nama permissions harus unique !!!',
        ]);

        $getPermissions = Permission::where('code_permissions', $permissionsCode)->firstOrFail();
        $getPermissions->update($validated);
        return response()->json([
            'message'           => 'Permissions berhasil diubah!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function destroy($permissionsCode)
    {
        $permissions = Permission::where('code_permissions', $permissionsCode)->firstOrFail();
        $permissions->delete();
        return response()->json([
            'message'           => 'Permissions berhasil dihapus!',
            'csrf_token'        => csrf_token(),
        ]);
    }
}
