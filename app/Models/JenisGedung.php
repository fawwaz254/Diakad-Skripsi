<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisGedung
 */
class JenisGedung extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_gedung';

    protected $primaryKey = 'id_jenis_gedung';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_jenis_gedung',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}