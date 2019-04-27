<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JadwalKelasMp
 */
class JadwalKelasMp extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_kelas_mp';

    protected $primaryKey = 'id_jadwal_kelas_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'id_ruangan',
        'id_jadwal_hari',
        'id_jadwal_jam',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}