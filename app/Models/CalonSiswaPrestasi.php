<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaPrestasi
 */
class CalonSiswaPrestasi extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_prestasi';

    protected $primaryKey = 'id_c_siswa_prestasi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_c_siswa',
        'id_tingkat_prestasi_siswa',
        'jenis_prestasi_c_siswa',
        'nm_prestasi_c_siswa',
        'lokasi_prestasi_c_siswa',
        'penyelenggara_prestasi_c_siswa',
        'peringkat_prestasi_c_siswa',
        'tgl_prestasi_c_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}