<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KelompokBiayaInternal
 */
class KelompokBiayaInternal extends Model
{
    use SoftDeletes;

    protected $table = 'kelompok_biaya_internal';

    protected $primaryKey = 'id_kelompok_biaya_internal';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_biaya',
        'nm_kelompok_biaya_internal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}