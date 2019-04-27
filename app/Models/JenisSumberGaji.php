<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisSumberGaji
 */
class JenisSumberGaji extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_sumber_gaji';

    protected $primaryKey = 'id_jenis_sumber_gaji';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_sumber_gaji',
        'nm_jenis_sumber_gaji',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}