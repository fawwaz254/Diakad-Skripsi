<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PenerimaanSyarat
 */
class PenerimaanSyarat extends Model
{
    use SoftDeletes;

    protected $table = 'penerimaan_syarat';

    protected $primaryKey = 'id_penerimaan_syarat';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_penerimaan',
        'id_jurusan',
        'nm_penerimaan_syarat',
        'is_wajib',
        'urutan',
        'is_upload_file',
        'keterangan_penerimaan_syarat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}