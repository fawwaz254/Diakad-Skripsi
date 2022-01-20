<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiHarian
 */
class PresensiPengguna extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_pengguna';

    protected $primaryKey = 'id_presensi_pengguna';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];






}
