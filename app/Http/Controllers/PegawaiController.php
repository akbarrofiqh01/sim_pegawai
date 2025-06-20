<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lihat pegawai')->only('index');
        $this->middleware('permission:tombol tambah pegawai')->only('create');
        $this->middleware('permission:tombol ubah pegawai')->only('edit');
        $this->middleware('permission:tombol hapus pegawai')->only('destroy');
        $this->middleware('permission:tombol roles pegawai')->only('roles');
    }

    public function index()
    {
        $pegawai = Pegawai::with(['user', 'jabatan'])->orderBy('id', 'DESC')->get();
        return view('page.pegawai.list_pegawai', compact('pegawai'));
    }

    public function create()
    {
        $dataJabatan = Jabatan::orderBy('id', 'DESC')->get();
        return view('page.pegawai.modal.tambah_pegawai', [
            'jabatan'       => $dataJabatan
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nipppp'     => ['required', 'numeric', 'digits_between:3,20'],
            'jbtan'      => ['required'],
            'fullname'   => ['required', 'string', 'min:3'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'min:5'],
            'stNikah'    => ['required'],
            'jnsk'       => ['required', Rule::in(['L', 'P'])], // ENUM tipe: L = Laki-laki, P = Perempuan
            'tmpLahir'   => ['required'],
            'tglLahir'   => ['required', 'date'],
            'agamages'   => ['required'],
            'noTelp'     => ['required', 'numeric', 'digits_between:8,15'],
            'almt'       => ['required']
        ], [
            'nipppp.required'       => 'Bagian NIP harus diisi !!!',
            'nipppp.numeric'        => 'Bagian NIP harus berupa angka !!!',
            'nipppp.digits_between' => 'Bagian NIP minimal 3 dan maksimal 20 digit !!!',

            'jbtan.required'        => 'Bagian jabatan wajib diisi !!!',
            'fullname.required'     => 'Bagian nama lengkap wajib diisi !!!',
            'fullname.min'          => 'Bagian nama lengkap minimal 3 karakter !!!',

            'email.required'        => 'Bagian email wajib diisi !!!',
            'email.email'           => 'Gunakan email yang valid !!!',
            'email.unique'          => 'Email sudah terdaftar, gunakan email lain !!!',

            'password.required'     => 'Bagian password wajib diisi !!!',
            'password.min'          => 'Password minimal 5 karakter !!!',

            'stNikah.required'      => 'Bagian status nikah wajib diisi !!!',
            'jnsk.required'         => 'Bagian jenis kelamin wajib diisi !!!',
            'jnsk.in'               => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan) !!!',

            'tmpLahir.required'     => 'Bagian tempat lahir wajib diisi !!!',
            'tglLahir.required'     => 'Bagian tanggal lahir wajib diisi !!!',
            'tglLahir.date'         => 'Tanggal lahir harus dalam format tanggal yang benar !!!',

            'agamages.required'     => 'Bagian agama wajib diisi !!!',

            'noTelp.required'       => 'Bagian no HP / WhatsApp wajib diisi !!!',
            'noTelp.numeric'        => 'Nomor HP harus berupa angka !!!',
            'noTelp.digits_between' => 'Nomor HP minimal 8 digit dan maksimal 15 digit !!!',

            'almt.required'         => 'Bagian alamat wajib diisi !!!',
        ]);


        $jabatan = Jabatan::where('code_jabatan', $request->jbtan)->firstOrFail();
        $nwPegawai = new Pegawai();
        $nwPegawai->jabatan_id          = $jabatan->id;
        $nwPegawai->fullname            = $request->fullname;
        $nwPegawai->nip                 = $request->nipppp;
        $nwPegawai->status_nikah        = $request->stNikah;
        $nwPegawai->jenis_kelamin       = $request->jnsk;
        $nwPegawai->tanggal_lahir       = $request->tglLahir;
        $nwPegawai->tempat_lahir        = $request->tmpLahir;
        $nwPegawai->agama               = $request->agamages;
        $nwPegawai->email               = $request->email;
        $nwPegawai->no_telp             = $request->noTelp;
        $nwPegawai->alamat              = $request->almt;
        $nwPegawai->code_pegawai        = Str::random(60);
        $nwPegawai->saveOrFail();

        $nwUser     = new User();
        $nwUser->name                   = $request->fullname;
        $nwUser->email                  = $request->email;
        $nwUser->password               = Hash::make($request->password);
        $nwUser->pegawai_id             = $nwPegawai->id;
        $nwUser->code_user              = Str::random(60);
        $nwUser->saveOrFail();
        return response()->json([
            'message'           => 'Data pegawai berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function edit($codePegawai)
    {
        $pegawai = Pegawai::where('code_pegawai', $codePegawai)->firstOrFail();
        // dd($pegawai);
        $dataJabatan = Jabatan::orderBy('id', 'DESC')->get();
        return view('page.pegawai.modal.ubah_pegawai', [
            'pegawai'       => $pegawai,
            'jabatan'       => $dataJabatan
        ]);
    }

    public function roles($cdPegawai)
    {
        $getPegawai = Pegawai::where('code_pegawai', $cdPegawai)->firstOrFail();
        $getUsers   = User::where('pegawai_id', $getPegawai->id)->firstOrFail();
        if (auth()->user()->hasRole('superadmin')) {
            $getRoles = Role::all();
        } else {
            $getRoles = Role::where('name', '!=', 'superadmin')
                ->orderBy('name', 'ASC')
                ->get();
        }
        $hasRoles = $getUsers->roles()->pluck('id');
        return view('page.pegawai.modal.roles_pegawai', [
            'userdata'          => $getUsers,
            'roles'             => $getRoles,
            'hasRoles'          => $hasRoles
        ]);
    }

    public function rolesUpdate(Request $request, $codePegawai)
    {
        $request->validate(
            [
                'role' => ['required'], // Validasi input
            ],
            [
                'role.required'         => ['Roles wajib dipilih, Minimal 1 Roles !!!']
            ]
        );

        $users = User::where('code_user', $codePegawai)->firstOrFail();
        if (!$users->hasRole($request->role)) {
            $users->syncRoles([$request->role]);
            $users->save();
        } else {
            $users->syncRoles($request->role);
            $users->update();
        }

        return response()->json([
            'message'    => 'Role pegawai berhasil ditambahkan!',
            'csrf_token' => csrf_token()
        ]);
    }

    public function update(Request $request, $codePegawai)
    {
        $request->validate([
            'nipppp'     => ['required', 'numeric', 'digits_between:3,20'],
            'jbtan'      => ['required'],
            'fullname'   => ['required', 'string', 'min:3'],
            'stNikah'    => ['required'],
            'jnsk'       => ['required', Rule::in(['L', 'P'])], // ENUM tipe: L = Laki-laki, P = Perempuan
            'tmpLahir'   => ['required'],
            'tglLahir'   => ['required', 'date'],
            'agamages'   => ['required'],
            'noTelp'     => ['required', 'numeric', 'digits_between:8,15'],
            'almt'       => ['required']
        ], [
            'nipppp.required'       => 'Bagian NIP harus diisi !!!',
            'nipppp.numeric'        => 'Bagian NIP harus berupa angka !!!',
            'nipppp.digits_between' => 'Bagian NIP minimal 3 dan maksimal 20 digit !!!',

            'jbtan.required'        => 'Bagian jabatan wajib diisi !!!',
            'fullname.required'     => 'Bagian nama lengkap wajib diisi !!!',
            'fullname.min'          => 'Bagian nama lengkap minimal 3 karakter !!!',

            'stNikah.required'      => 'Bagian status nikah wajib diisi !!!',
            'jnsk.required'         => 'Bagian jenis kelamin wajib diisi !!!',
            'jnsk.in'               => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan) !!!',

            'tmpLahir.required'     => 'Bagian tempat lahir wajib diisi !!!',
            'tglLahir.required'     => 'Bagian tanggal lahir wajib diisi !!!',
            'tglLahir.date'         => 'Tanggal lahir harus dalam format tanggal yang benar !!!',

            'agamages.required'     => 'Bagian agama wajib diisi !!!',

            'noTelp.required'       => 'Bagian no HP / WhatsApp wajib diisi !!!',
            'noTelp.numeric'        => 'Nomor HP harus berupa angka !!!',
            'noTelp.digits_between' => 'Nomor HP minimal 8 digit dan maksimal 15 digit !!!',

            'almt.required'         => 'Bagian alamat wajib diisi !!!',
        ]);


        $jabatan = Jabatan::where('code_jabatan', $request->jbtan)->firstOrFail();
        $pegawai = Pegawai::where('code_pegawai', $codePegawai)->firstOrFail();
        $users   = User::where('pegawai_id', $pegawai->id)->firstOrFail();

        $pegawai->jabatan_id          = $jabatan->id;
        $pegawai->fullname            = $request->fullname;
        $pegawai->nip                 = $request->nipppp;
        $pegawai->status_nikah        = $request->stNikah;
        $pegawai->jenis_kelamin       = $request->jnsk;
        $pegawai->tanggal_lahir       = $request->tglLahir;
        $pegawai->tempat_lahir        = $request->tmpLahir;
        $pegawai->agama               = $request->agamages;
        $pegawai->no_telp             = $request->noTelp;
        $pegawai->alamat              = $request->almt;
        $pegawai->update();

        $users->name                   = $request->fullname;
        $users->pegawai_id             = $pegawai->id;
        $users->update();
        return response()->json([
            'message'           => 'Data pegawai berhasil diperbarui!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function destroy($codePegawai)
    {
        $pegawai = Pegawai::where('code_pegawai', $codePegawai)->firstOrFail();
        $users   = User::where('pegawai_id', $pegawai->id)->firstOrFail();

        $users->delete();
        $pegawai->delete();
        return response()->json([
            'message'           => 'Data pegawai berhasil dihapus!',
            'csrf_token'        => csrf_token()
        ]);
    }
}
