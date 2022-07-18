<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaSyarat
 */
class CalonSiswaSyarat extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_syarat';

    protected $primaryKey = 'id_c_siswa_syarat';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_c_siswa',
        'id_penerimaan_syarat',
        'nm_file_syarat',
        'nm_file_asli',
        'is_verified',
        'pesan_verifikator',
        'tgl_valid_syarat',
        'tgl_invalid_syarat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}