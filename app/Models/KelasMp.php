<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KelasMp
 */
class KelasMp extends Model
{
    use SoftDeletes;

    protected $table = 'kelas_mp';

    protected $primaryKey = 'id_kelas_mp';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester',
        'id_kelas',
        'id_mata_pelajaran',
        'nm_kelas_mp',
        'jml_pertemuan_kelas_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }

    public function kelas_mp_grup()
    {
        return $this->belongsTo('App\Models\KelasMpGrup', 'id_kelas_mp_grup');
    }

    public function jadwal_kelas_mp()
    {
        return $this->hasMany('App\Models\JadwalKelasMp', 'id_kelas_mp');
    }

    public function pengambilan_mp()
    {
        return $this->hasMany('App\Models\PengambilanMp', 'id_kelas_mp');
    }

    public function pengampu_mp_utama()
    {
        return $this->hasOne('App\Models\PengampuMp', 'id_kelas_mp')->where('pjmp_pengampu_mp', 1);
    }

    public function pengampu_mp()
    {
        return $this->hasMany('App\Models\PengampuMp', 'id_kelas_mp');
    }

    public function semester()
    {
        return $this->belongsTo('App\Models\Semester', 'id_semester');
    }

    public function mata_pelajaran()
    {
        return $this->belongsTo('App\Models\MataPelajaran', 'id_mata_pelajaran');
    }

    public function presensi_mp()
    {
        return $this->hasMany('App\Models\PresensiMp', 'id_kelas_mp');
    }
}
