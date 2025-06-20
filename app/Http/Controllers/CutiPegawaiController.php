<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CutiPegawai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CutiPegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lihat cuti')->only('index');
        $this->middleware('permission:tombol tambah cuti')->only('create');
        $this->middleware('permission:tombol ubah cuti')->only('edit');
        $this->middleware('permission:tombol hapus cuti')->only('destroy');
    }

    public function index()
    {
        $userdata = auth()->user()->id;
        $listttCuti = CutiPegawai::where('pegawai_id', $userdata)
            ->with(['pegawai.user'])
            ->latest()
            ->get();
        return view('page.cuti.cuti', compact('listttCuti'));
    }

    public function create()
    {
        return view('page.cuti.modal.tambah_cuti');
    }

    public function store(Request $request)
    {
        $userdata = auth()->user()->id;
        $request->validate(
            [
                'jnsCuti'           => ['required', Rule::in(['tahunan', 'izin', 'sakit', 'melahirkan', 'penting'])],
                'tglMulai'          => ['required'],
                'tglAkhir'          => ['required'],
                'alasanCuti'        => ['required'],
            ],
            [
                'jnsCuti.required'       => 'Bagian jenis cuti harus diisi !!!',
                'jnsCuti.in'             => 'Bagian jenis cuti harus Tahunan, Izin, Sakit, Melahirkan, Penting !!!',
                'tglMulai.required'      => 'Bagian tanggal mulai cuti harus diisi !!!',
                'tglAkhir.required'      => 'Bagian tanggal akhir cuti harus diisi !!!',
            ]
        );

        $nwPengajuanCuti = new CutiPegawai();
        $nwPengajuanCuti->pegawai_id                   =    $userdata;
        $nwPengajuanCuti->jenis_cuti                   =    $request->jnsCuti;
        $nwPengajuanCuti->tanggal_mulai                =    $request->tglMulai;
        $nwPengajuanCuti->tanggal_selesai              =    $request->tglAkhir;
        $nwPengajuanCuti->alasan                       =    $request->alasanCuti;
        $nwPengajuanCuti->disetujui_oleh_userid        =    NULL;
        $nwPengajuanCuti->code_cuti_pegawai            =    Str::random(60);
        $nwPengajuanCuti->saveOrFail();
        return response()->json([
            'message'           => 'Pengajuan cuti berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function edit($codeCuti)
    {
        $cuti = CutiPegawai::where('code_cuti_pegawai', $codeCuti)->firstOrFail();
        return view('page.cuti.modal.edit_cuti', compact('cuti'));
    }

    public function update(Request $request, $codeCuti)
    {
        $userdata = auth()->user()->id;
        $request->validate(
            [
                'jnsCuti'           => ['required', Rule::in(['tahunan', 'izin', 'sakit', 'melahirkan', 'penting'])],
                'tglMulai'          => ['required'],
                'tglAkhir'          => ['required'],
                'alasanCuti'        => ['required'],
            ],
            [
                'jnsCuti.required'       => 'Bagian jenis cuti harus diisi !!!',
                'jnsCuti.in'             => 'Bagian jenis cuti harus Tahunan, Izin, Sakit, Melahirkan, Penting !!!',
                'tglMulai.required'      => 'Bagian tanggal mulai cuti harus diisi !!!',
                'tglAkhir.required'      => 'Bagian tanggal akhir cuti harus diisi !!!',
            ]
        );

        $cuti = CutiPegawai::where('code_cuti_pegawai', $codeCuti)->firstOrFail();
        $cuti->pegawai_id           = $userdata;
        $cuti->jenis_cuti           = $request->jnsCuti;
        $cuti->tanggal_mulai        = $request->tglMulai;
        $cuti->tanggal_selesai      = $request->tglAkhir;
        $cuti->alasan               = $request->alasanCuti;
        $cuti->update();
        return response()->json([
            'message'           => 'Pengajuan cuti berhasil diperbarui!',
            'csrf_token'        => csrf_token()
        ]);
    }

    public function destroy($codeCuti)
    {
        $cuti = CutiPegawai::where('code_cuti_pegawai', $codeCuti)->firstOrFail();
        $cuti->delete();
        return response()->json([
            'message'           => 'Pengajuan cuti berhasil dihapus!',
            'csrf_token'        => csrf_token()
        ]);
    }
}
