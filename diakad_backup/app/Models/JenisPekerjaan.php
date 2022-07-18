<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisPekerjaan
 */
class JenisPekerjaan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_pekerjaan';

    protected $primaryKey = 'id_jenis_pekerjaan';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_pekerjaan',
        'nm_jenis_pekerjaan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}