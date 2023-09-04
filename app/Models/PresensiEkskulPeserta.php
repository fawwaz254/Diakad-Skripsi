<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiEkskulPeserta
 */
class PresensiEkskulPeserta extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_ekskul_peserta';

    protected $primaryKey = 'id_presensi_ekskul_peserta';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_presensi_ekskul',
        'id_siswa',
        'kehadiran',
        'alasan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function presensi_ekskul()
    {
        return $this->belongsTo('App\Models\PresensiEkskul', 'id_presensi_ekskul');
    }
}
