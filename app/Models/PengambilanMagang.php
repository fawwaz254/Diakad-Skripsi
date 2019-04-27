<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengambilanMagang
 */
class PengambilanMagang extends Model
{
    use SoftDeletes;

    protected $table = 'pengambilan_magang';

    protected $primaryKey = 'id_pengambilan_magang';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_periode_magang',
        'id_rekanan_magang',
        'id_siswa',
        'status_apv_pengambilan_magang',
        'nilai_angka',
        'nilai_huruf',
        'is_tampil',
        'status_magang',
        'keterangan_batal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}