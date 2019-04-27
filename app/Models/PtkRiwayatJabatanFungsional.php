<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkRiwayatJabatanFungsional
 */
class PtkRiwayatJabatanFungsional extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_riwayat_jabatan_fungsional';

    protected $primaryKey = 'id_ptk_riwayat_jabatan_fungsional';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'nm_jabatan_fungsional',
        'nomor_sk_jabatan_fungsional',
        'tgl_sk_jabatan_fungsional',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}