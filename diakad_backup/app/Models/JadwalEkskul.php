<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JadwalEkskul
 */
class JadwalEkskul extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_ekskul';

    protected $primaryKey = 'id_jadwal_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ekskul',
        'id_jadwal_hari',
        'id_jadwal_jam',
        'tempat_jadwal_ekskul',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}