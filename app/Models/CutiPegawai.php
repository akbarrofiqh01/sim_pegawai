<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutiPegawai extends Model
{
    protected $table = 'cuti_pegawais';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh_userid');
    }
}
