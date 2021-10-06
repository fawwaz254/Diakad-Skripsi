<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class Agama extends Model
{
    use SoftDeletes;

    protected $table = 'agama';

    protected $primaryKey = 'id_agama';

	public $timestamps = true;

    protected $fillable = [
        'kode_agama',
        'nm_agama',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

}