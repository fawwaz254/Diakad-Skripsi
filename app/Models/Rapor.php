<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Rapor extends Model
{
    use SoftDeletes;

    protected $table = 'rapor';

    protected $primaryKey = 'id_rapor';

    public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }
    public function nilai_rapor()
    {
        return $this->hasMany(NilaiRapor::class, 'id_rapor', 'id_rapor');
    }

    public function keterangan_rapor()
    {
        return $this->hasMany(KeteranganRapor::class, 'id_rapor');
    }
}
