<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengampuMapel
 */
class PengampuMapel extends Model
{
    use SoftDeletes;

    protected $table = 'pengampu_mapel';

    protected $primaryKey = 'id_pengampu_mapel';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    public function mata_pelajaran()
    {
        return $this->belongsTo('App\Models\MataPelajaran', 'id_mata_pelajaran');
    }
}
