<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisLayakPip
 */
class JenisLayakPip extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_layak_pip';

    protected $primaryKey = 'id_jenis_layak_pip';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_layak_pip',
        'nm_jenis_layak_pip',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}