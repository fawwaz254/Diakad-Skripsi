<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkSertifikasi
 */
class PtkSertifikasi extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_sertifikasi';

    protected $primaryKey = 'id_ptk_sertifikasi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_sertifikasi',
        'nomor_sertifikasi',
        'tahun_sertifikasi',
        'bidang_studi_sertifikasi',
        'nrg_ptk',
        'nomor_peserta_sertifikasi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}