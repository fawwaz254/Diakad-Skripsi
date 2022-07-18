<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiMpSiswa
 */
class PresensiMpSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_mp_siswa';

    protected $primaryKey = 'id_presensi_mp_siswa';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_presensi_mp',
        'id_siswa',
        'kehadiran',
        'alasan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function presensi_mp()
    {
        return $this->belongsTo('App\Models\PresensiMp', 'id_presensi_mp');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }
}
