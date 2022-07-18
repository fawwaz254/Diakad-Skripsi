<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KegiatanHarianKategori
 */
class KegiatanHarianKategori extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_harian_kategori';

    protected $primaryKey = 'id_kegiatan_harian_kategori';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function kegiatan_harian()
    {
        return $this->belongsTo(KegiatanHarian::class, 'id_kegiatan_harian');
    }

    public function pertanyaan()
    {
        return $this->hasMany(KegiatanHarianPertanyaan::class, 'id_kegiatan_harian_kategori');
    }
}