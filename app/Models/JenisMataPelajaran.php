<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisMataPelajaran
 */
class JenisMataPelajaran extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_mata_pelajaran';

    protected $primaryKey = 'id_jenis_mata_pelajaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'kode_jenis_mata_pelajaran',
        'nm_jenis_mata_pelajaran',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}