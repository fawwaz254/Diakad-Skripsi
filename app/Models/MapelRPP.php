<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MapelRPP
 */
class MapelRPP extends Model
{
    protected $table = 'mapel_rpp';

    protected $primaryKey = 'id_mapel_rpp';

    public $timestamps = true;

    public $incrementing = true;

    protected $guarded = [];

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
}
