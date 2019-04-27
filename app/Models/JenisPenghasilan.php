<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisPenghasilan
 */
class JenisPenghasilan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_penghasilan';

    protected $primaryKey = 'id_jenis_penghasilan';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_penghasilan',
        'nm_jenis_penghasilan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}