<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkGajiBerkala
 */
class PtkGajiBerkala extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_gaji_berkala';

    protected $primaryKey = 'id_ptk_gaji_berkala';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'pangkat_golongan_gaji_berkala',
        'nomor_sk_gaji_berkala',
        'tgl_sk_gaji_berkala',
        'tgl_mulai_gaji_berkala',
        'masa_kerja_tahun_gaji_berkala',
        'masa_kerja_bulan_gaji_berkala',
        'gaji_pokok',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}