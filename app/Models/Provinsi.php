<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Provinsi
 */
class Provinsi extends Model
{
    use SoftDeletes;

    protected $table = 'provinsi';

    protected $primaryKey = 'id_provinsi';

	public $timestamps = true;

    protected $fillable = [
        'nm_provinsi',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}