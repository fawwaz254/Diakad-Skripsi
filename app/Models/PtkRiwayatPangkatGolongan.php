<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkRiwayatPangkatGolongan
 */
class PtkRiwayatPangkatGolongan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_riwayat_pangkat_golongan';

    protected $primaryKey = 'id_ptk_riwayat_pangkat_golongan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'nm_pangkat_golongan',
        'nomor_sk_pangkat_golongan',
        'tgl_sk_pangkat_golongan',
        'tmt_pangkat_golongan',
        'masa_kerja_tahun_pangkat_golongan',
        'masa_kerja_bulan_pangkat_golongan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}