<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JadwalHari
 */
class JadwalHari extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_hari';

    protected $primaryKey = 'id_jadwal_hari';

	public $timestamps = true;

    protected $fillable = [
        'kode_jadwal_hari',
        'nm_jadwal_hari',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}