<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PresensiMagangSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_magang_siswa';

    protected $primaryKey = 'id_presensi_magang_siswa';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_presensi_magang',
        'id_siswa',
        'kehadiran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function presensiMagang()
    {
        return $this->belongsTo(PresensiMagang::class, 'id_presensi_magang', 'id_presensi_magang');
    }

    public function pembimbingMagang()
    {
        return $this->belongsTo(PembimbingMagang::class, 'id_pembimbing_magang');
    }
}
