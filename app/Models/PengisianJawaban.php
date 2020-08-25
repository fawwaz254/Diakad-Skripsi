<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengisianJawaban
 */
class PengisianJawaban extends Model
{
    use SoftDeletes;

    protected $table = 'pengisian_jawaban';

    protected $primaryKey = 'id_pengisian_jawaban';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function pertanyaan()
    {
        return $this->belongsTo(KegiatanHarianPertanyaan::class, 'id_kegiatan_harian_pertanyaan');
    }

    public function jawaban()
    {
        return $this->belongsTo(KegiatanHarianJawaban::class, 'id_kegiatan_harian_jawaban');
    }

}