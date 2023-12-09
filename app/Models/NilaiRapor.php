<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class NilaiRapor extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_rapor';

    protected $primaryKey = 'id_nilai_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    public function rapor()
    {
        return $this->belongsTo(Rapor::class, 'id_rapor');
    }

    public function komponen_jenis_rapor()
    {
        return $this->belongsTo(KomponenJenisRapor::class, 'id_komponen_jenis_rapor');
    }
}
