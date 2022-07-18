<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiMp
 */
class PresensiMp extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_mp';

    protected $primaryKey = 'id_presensi_mp';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'id_jadwal_kelas_mp',
        'pertemuan_ke',
        'uraian_materi',
        'waktu_mulai',
        'waktu_selesai',
        'tgl_presensi',
        'id_guru_pengganti',
        'alasan_tidak_hadir',
        'tgl_entry',
        'persentase_presensi_mp',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function jadwal_kelas_mp()
    {
        return $this->belongsTo('App\Models\JadwalKelasMp', 'id_jadwal_kelas_mp');
    }

    public function kelas_mp()
    {
        return $this->belongsTo('App\Models\KelasMp', 'id_kelas_mp');
    }

    public function materi(){

        return $this->hasMany('App\Models\PresensiMpMateri', 'id_presensi_mp');

    }

    public function jenis_materi_to_text()
    {
        switch($this->jenis_materi){
            case 1:
                return 'KBM';
            case 2:
                return 'UH';
            case 3:
                return 'UTS';
            case 4:
                return 'UAS';
            default:
                return '-';
        }
    }

    public function presensi_mp_siswa()
    {
        return $this->hasMany('App\Models\PresensiMpSiswa', 'id_presensi_mp');
    }
}
