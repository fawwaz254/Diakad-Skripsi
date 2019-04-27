<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Biaya
 */
class Biaya extends Model
{
    use SoftDeletes;

    protected $table = 'biaya';

    protected $primaryKey = 'id_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_biaya',
        'keterangan_biaya',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}