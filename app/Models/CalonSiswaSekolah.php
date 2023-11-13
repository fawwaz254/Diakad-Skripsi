<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaSekolah
 */
class CalonSiswaSekolah extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_sekolah';

    protected $primaryKey = 'id_c_siswa';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_c_siswa',
        'nm_sekolah_asal',
        'id_kota_sekolah_asal',
        'nomor_shun',
        'nilai_shun',
        'nomor_ijasah',
        'tahun_lulus',
        'nomor_peserta_unas',
        'nisn',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function kota_asal_sekolah()
    {
        return $this->belongsTo(Kota::class, 'id_kota_sekolah_asal');
    }
}
