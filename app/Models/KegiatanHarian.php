<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KegiatanHarian
 */
class KegiatanHarian extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_harian';

    protected $primaryKey = 'id_kegiatan_harian';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function kategori_pertanyaan()
    {
        return $this->hasMany(KegiatanHarianKategori::class, 'id_kegiatan_harian');
    }

    public function pengisisan_kegiatan_harian(){
        return $this->hasMany(PengisianKegiatanHarian::class, 'id_kegiatan_harian');

    }

    public function is_aktif_to_text()
    {
        switch($this->is_aktif){
            case 0: 
                return 'Non-Aktif';
            case 1: 
                return 'Aktif';
            default: 
                return '';
        }
    }
}