<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisRuangan
 */
class JenisRuangan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_ruangan';

    protected $primaryKey = 'id_jenis_ruangan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_jenis_ruangan',
        'tipe_ruangan',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}