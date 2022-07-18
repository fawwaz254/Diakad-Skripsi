<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisKeahlianLab
 */
class JenisKeahlianLab extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_keahlian_lab';

    protected $primaryKey = 'id_jenis_keahlian_lab';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_keahlian_lab',
        'nm_jenis_keahlian_lab',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}