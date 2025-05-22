<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengambilanMagang
 */
class PengambilanMagang extends Model
{
    use SoftDeletes;

    protected $table = 'pengambilan_magang';

    protected $primaryKey = 'id_pengambilan_magang';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_periode_magang',
        'id_rekanan_magang',
        'id_siswa',
        'status_apv_pengambilan_magang',
        'nilai_angka',
        'nilai_huruf',
        'is_tampil',
        'status_magang',
        'keterangan_batal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(PeriodeMagang::class, 'id_periode_magang');
    }

    public function rekanan()
    {
        return $this->belongsTo(RekananMagang::class, 'id_rekanan_magang');
    }

    public function pembimbingMagang()
    {
        return $this->hasMany(PembimbingMagang::class, 'id_rekanan_magang', 'id_rekanan_magang');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function createdBy()
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    public function presensiMagangSiswa()
    {
        return $this->belongsTo(PresensiMagangSiswa::class, 'id_siswa', 'id_siswa');
    }
}
