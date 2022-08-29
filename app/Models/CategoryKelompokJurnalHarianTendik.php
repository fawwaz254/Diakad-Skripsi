<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CategoryKelompokJurnalHarianTendik extends Model
{
  use SoftDeletes;

  protected $table = 'category_kelompok_jurnal_harian_tendik';

  protected $primaryKey = 'id_c_k_jh_tendik';

  public $incrementing = false;

  public $timestamps = true;

  protected $guarded = [];
  public function pengguna()
  {
      return $this->belongsTo(pengguna::class, 'id_pengguna', 'id_pengguna');
  }
  public function category_jurnal_harian_tendik(){
      return $this->belongsTo(CategoryJurnalHarianTendik::class, 'id_category_jh_tendik');
  }

  public function laporan_kerja_harian_tendik(){
      return $this->hasMany(LaporanKerjaHarianTendik::class, 'id_category_jh_tendik','id_category_jh_tendik');
  }
}
