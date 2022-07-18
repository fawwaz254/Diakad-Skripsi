<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JadwalKegiatan
 */
class JadwalKegiatan extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_kegiatan';

    protected $primaryKey = 'id_jadwal_kegiatan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kegiatan',
        'id_semester',
        'tgl_mulai',
        'tgl_selesai',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}