<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisTransportasi
 */
class JenisTransportasi extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_transportasi';

    protected $primaryKey = 'id_jenis_transportasi';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_transportasi',
        'nm_jenis_transportasi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}