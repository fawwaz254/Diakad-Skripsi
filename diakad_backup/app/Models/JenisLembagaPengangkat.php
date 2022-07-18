<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisLembagaPengangkat
 */
class JenisLembagaPengangkat extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_lembaga_pengangkat';

    protected $primaryKey = 'id_jenis_lembaga_pengangkat';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_lembaga_pengangkat',
        'nm_jenis_lembaga_pengangkat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}