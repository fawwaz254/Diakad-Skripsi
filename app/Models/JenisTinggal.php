<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisTinggal
 */
class JenisTinggal extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_tinggal';

    protected $primaryKey = 'id_jenis_tinggal';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_tinggal',
        'nm_jenis_tinggal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}