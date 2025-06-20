<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lihat data jabatan')->only('index');
        $this->middleware('permission:tombol tambah jabatan')->only('create');
        $this->middleware('permission:tombol ubah jabatan')->only('edit');
        $this->middleware('permission:tombol hapus jabatan')->only('destroy');
    }

    public function index()
    {
        $jabatan = Jabatan::orderBy('id', 'DESC')->get();
        return view('page.jabatan.list_jabatan', compact('jabatan'));
    }

    public function create()
    {
        return view('page.jabatan.modal.tambah_jabatan');
    }

    public function store(Request $request)
    {
        $request->merge([
            'gajiPokok' => str_replace('.', '', $request->gajiPokok),
        ]);

        $request->validate(
            [
                'nameJabatan' => ['required', 'min:3'],
                'gajiPokok'   => ['required', 'numeric', 'digits_between:1,15']
            ],
            [
                'nameJabatan.required'     => 'Bagian nama jabatan wajib diisi !!!',
                'nameJabatan.min'          => 'Bagian nama jabatan minimal 3 karakter !!!',
                'gajiPokok.required'       => 'Bagian gaji pokok wajib diisi !!!',
                'gajiPokok.numeric'        => 'Bagian gaji pokok harus berupa numeric !!!',
                'gajiPokok.digits_between' => 'Bagian gaji pokok harus antara 1 sampai 15 digit !!!',
            ]
        );

        $nwJabatan = new Jabatan();
        $nwJabatan->nama_jabatan            = $request->nameJabatan;
        $nwJabatan->gaji_pokok              = $request->gajiPokok;
        $nwJabatan->code_jabatan            = Str::random(60);
        $nwJabatan->saveOrFail();
        return response()->json([
            'message'           => 'Data jabatan berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function edit($codeJabatan)
    {
        $datajabatan = Jabatan::where('code_jabatan', $codeJabatan)->firstOrFail();
        return view('page.jabatan.modal.ubah_jabatan', compact('datajabatan'));
    }

    public function update(Request $request, $codeJabatan)
    {
        $request->merge([
            'gajiPokok' => str_replace('.', '', $request->gajiPokok),
        ]);

        $request->validate(
            [
                'nameJabatan' => ['required', 'min:3'],
                'gajiPokok'   => ['required', 'numeric', 'digits_between:1,15']
            ],
            [
                'nameJabatan.required'     => 'Bagian nama jabatan wajib diisi !!!',
                'nameJabatan.min'          => 'Bagian nama jabatan minimal 3 karakter !!!',
                'gajiPokok.required'       => 'Bagian gaji pokok wajib diisi !!!',
                'gajiPokok.numeric'        => 'Bagian gaji pokok harus berupa numeric !!!',
                'gajiPokok.digits_between' => 'Bagian gaji pokok harus antara 1 sampai 15 digit !!!',
            ]
        );
        $updJabatan = Jabatan::where('code_jabatan', $codeJabatan)->firstOrFail();
        $updJabatan->nama_jabatan            = $request->nameJabatan;
        $updJabatan->gaji_pokok              = $request->gajiPokok;
        $updJabatan->update();
        return response()->json([
            'message'           => 'Data jabatan berhasil diubah!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function destroy($codeJabatan)
    {
        $datajabatan = Jabatan::where('code_jabatan', $codeJabatan)->firstOrFail();
        $datajabatan->delete();
        return response()->json([
            'message'           => 'Data jabatan berhasil dihapus!',
            'csrf_token'        => csrf_token(),
        ]);
    }
}
