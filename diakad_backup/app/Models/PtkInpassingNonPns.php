<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkInpassingNonPns
 */
class PtkInpassingNonPns extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_inpassing_non_pns';

    protected $primaryKey = 'id_ptk_inpassing_non_pns';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'pangkat_golongan_inpassing',
        'nomor_sk_inpassing',
        'tgl_sk_inpassing',
        'tgl_mulai_inpassing',
        'angka_kridit_inpassing',
        'masa_kerja_tahun_inpassing',
        'masa_kerja_bulan_inpassing',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}