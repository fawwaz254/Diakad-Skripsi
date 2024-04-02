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
        'id_jadwal_jam_selesai',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kelas_mp()
    {
        return $this->belongsTo('App\Models\KelasMp', 'id_kelas_mp');
    }

    public function ruangan()
    {
        return $this->belongsTo('App\Models\Ruangan', 'id_ruangan');
    }

    public function jadwal_hari()
    {
        return $this->belongsTo('App\Models\JadwalHari', 'id_jadwal_hari');
    }

    public function jadwal_jam_mulai()
    {
        return $this->belongsTo('App\Models\JadwalJam', 'id_jadwal_jam');
    }

    public function jadwal_jam_selesai()
    {
        return $this->belongsTo('App\Models\JadwalJam', 'id_jadwal_jam_selesai', 'id_jadwal_jam');
    }
}
