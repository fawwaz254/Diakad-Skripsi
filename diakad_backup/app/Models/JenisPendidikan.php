<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisPendidikan
 */
class JenisPendidikan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_pendidikan';

    protected $primaryKey = 'id_jenis_pendidikan';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_pendidikan',
        'nm_jenis_pendidikan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}