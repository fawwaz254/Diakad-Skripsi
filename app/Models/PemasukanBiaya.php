<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PemasukanBiaya
 */
class PemasukanBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'pemasukan_biaya';

    protected $primaryKey = 'id_pemasukan_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pemasukan_biaya_subkategori',
        'id_semester',
        'id_staff',
        'tgl_pemasukan_biaya',
        'besar_pemasukan_biaya',
        'keterangan_pemasukan_biaya',
        'is_upload_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}