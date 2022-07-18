<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkAnak
 */
class PtkAnak extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_anak';

    protected $primaryKey = 'id_ptk_anak';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'nm_anak',
        'status_anak',
        'jenjang_pendidikan_anak',
        'nisn_anak',
        'jenis_kelamin_anak',
        'id_kota_lahir_anak',
        'tgl_lahir_anak',
        'tahun_masuk_pendidikan_anak',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}