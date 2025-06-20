<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lihat role user')->only('index');
        $this->middleware('permission:tombol tambah role user')->only('create');
        $this->middleware('permission:tombol ubah role user')->only('edit');
        $this->middleware('permission:tombol hapus role user')->only('destroy');
    }
    public function index()
    {
        $roles = Role::all()->sortByDesc('id');
        return view('page.pengaturan.list_role', compact('roles'));
    }

    public function create()
    {
        $getPermissions = Permission::orderBy('name', 'ASC')->get();
        return view('page.pengaturan.modal.tambah_roles', compact('getPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => ['required', 'unique:roles', 'min:3'],
        ], [
            'name.required'        => 'Bagian nama roles wajib diisi !!!',
            'name.unique'        => 'Bagian nama roles harus unique !!!',
        ]);

        $createRoles = new Role();
        $createRoles->name = strtolower($request->name);
        $createRoles->code_role = Str::random(60);
        if (!empty($request->permissions)) {
            foreach ($request->permissions as $nameValue) {
                $createRoles->givePermissionTo($nameValue);
            }
        }
        $createRoles->saveOrFail();
        return response()->json([
            'message'           => 'Roles berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function edit($codeRoles)
    {
        $roles = Role::where('code_role', $codeRoles)->firstOrFail();
        $Haspermissions = $roles->permissions->pluck('name');
        $getPermissions = Permission::orderBy('name', 'ASC')->get();
        return view('page.pengaturan.modal.ubah_roles', [
            'dataRole'              => $roles,
            'dataPermissions'       => $getPermissions,
            'hasPermissions'        => $Haspermissions
        ]);
    }

    public function update(Request $request, $codeRoles)
    {
        // dd($request->all());
        $request->validate([
            'valName' => [
                'required',
                'min:3',
                Rule::unique('roles', 'name')->ignore($codeRoles, 'code_role'),
            ],
        ], [
            'valName.required' => 'Bagian nama roles wajib diisi !!!',
            'valName.unique'   => 'Bagian nama roles harus unique !!!',
        ]);

        $getRoles = Role::where('code_role', $codeRoles)->firstOrFail();
        $getRoles->name = strtolower($request->valName);
        $getRoles->save(); // Simpan perubahan nama role

        // Sinkronisasi permission (bisa kosong)
        $getRoles->syncPermissions($request->input('selectedPermissions', []));

        return response()->json([
            'message'     => 'Roles berhasil diubah!',
            'csrf_token'  => csrf_token()
        ]);
    }
}
