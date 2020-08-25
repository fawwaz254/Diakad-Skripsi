<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KegiatanHarianPertanyaan
 */
class KegiatanHarianPertanyaan extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_harian_pertanyaan';

    protected $primaryKey = 'id_kegiatan_harian_pertanyaan';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function kategori_pertanyaan()
    {
        return $this->belongsTo(KegiatanHarianKategori::class, 'id_kegiatan_harian_kategori');
    }

    public function jawaban()
    {
        return $this->hasMany(KegiatanHarianJawaban::class, 'id_kegiatan_harian_pertanyaan');
    }
}