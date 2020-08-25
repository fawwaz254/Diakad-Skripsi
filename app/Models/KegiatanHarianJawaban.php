<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KegiatanHarianJawaban
 */
class KegiatanHarianJawaban extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_harian_jawaban';

    protected $primaryKey = 'id_kegiatan_harian_jawaban';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];
}