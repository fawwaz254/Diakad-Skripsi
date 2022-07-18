<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisPtk
 */
class JenisPtk extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_ptk';

    protected $primaryKey = 'id_jenis_ptk';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_ptk',
        'nm_jenis_ptk',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}