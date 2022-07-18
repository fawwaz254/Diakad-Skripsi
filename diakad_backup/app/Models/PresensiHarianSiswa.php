<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiHarianSiswa
 */
class PresensiHarianSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_harian_siswa';

    protected $primaryKey = 'id_presensi_harian_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_presensi_harian',
        'id_siswa',
        'kehadiran',
        'alasan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function presensi_harian()
    {
        return $this->belongsTo(PresensiHarian::class, 'id_presensi_harian');
    }

}