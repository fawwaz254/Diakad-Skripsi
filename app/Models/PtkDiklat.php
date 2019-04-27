<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkDiklat
 */
class PtkDiklat extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_diklat';

    protected $primaryKey = 'id_ptk_diklat';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_diklat',
        'nm_diklat',
        'nomor_sertifikat_diklat',
        'penyelenggara_diklat',
        'tahun_diklat',
        'peran_diklat',
        'tingkat_diklat',
        'lama_jam_diklat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}