<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkRiwayatJabatan
 */
class PtkRiwayatJabatan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_riwayat_jabatan';

    protected $primaryKey = 'id_ptk_riwayat_jabatan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_jabatan',
        'nomor_sk_jabatan',
        'tgl_sk_jabatan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}