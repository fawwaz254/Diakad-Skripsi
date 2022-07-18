<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiMpMateri
 */
class PresensiMpMateri extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_mp_materi';

    protected $primaryKey = 'id_presensi_mp_materi';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];
}
