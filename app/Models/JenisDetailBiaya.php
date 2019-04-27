<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class JenisDetailBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_detail_biaya';

    protected $primaryKey = 'id_jenis_detail_biaya';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_detail_biaya',
        'nm_jenis_detail_biaya',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}