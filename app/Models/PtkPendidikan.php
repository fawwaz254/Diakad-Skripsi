<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkPendidikan
 */
class PtkPendidikan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_pendidikan';

    protected $primaryKey = 'id_ptk_pendidikan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'bidang_studi_pendidikan',
        'jenjang_pendidikan',
        'gelar_akademik_pendidikan',
        'nm_satuan_pendidikan_formal',
        'fakultas_pendidikan',
        'status_kependidikan',
        'tahun_masuk',
        'tahun_lulus',
        'nomor_induk_pendidikan',
        'is_masih_studi',
        'jumlah_semester_pendidikan',
        'rata_rata_nilai',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}