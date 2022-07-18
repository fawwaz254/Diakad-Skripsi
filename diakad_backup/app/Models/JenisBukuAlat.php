<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisBukuAlat
 */
class JenisBukuAlat extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_buku_alat';

    protected $primaryKey = 'id_jenis_buku_alat';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'kode_jenis_buku_alat',
        'nm_jenis_buku_alat',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}