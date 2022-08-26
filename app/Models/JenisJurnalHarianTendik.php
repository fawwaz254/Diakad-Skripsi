<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisJurnalHarianTendik extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_jurnal_harian_tendik';

    protected $primaryKey = 'id_jenis_jurhart';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];
}
