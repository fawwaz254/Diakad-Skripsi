<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KelompokBiaya
 */
class KelompokBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'kelompok_biaya';

    protected $primaryKey = 'id_kelompok_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_kelompok_biaya',
        'keterangan_kelompok_biaya',
        'status_kelompok_biaya',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}