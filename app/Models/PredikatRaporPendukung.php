<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PredikatRaporPendukung extends Model
{
    use SoftDeletes;

    protected $table = 'predikat_rapor_pendukung';

    protected $primaryKey = 'id_predikat_rapor_pendukung';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function indikator_rapor_pendukung()
    {
        return $this->belongsTo(IndikatorRaporPendukung::class, 'id_indikator_rapor_pendukung');
    }
}
