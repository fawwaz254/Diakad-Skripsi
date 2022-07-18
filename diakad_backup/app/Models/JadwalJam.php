<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JadwalJam
 */
class JadwalJam extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_jam';

    protected $primaryKey = 'id_jadwal_jam';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_jadwal_jam',
        'jam_ke',
        'jam_mulai',
        'menit_mulai',
        'jam_selesai',
        'menit_selesai',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}