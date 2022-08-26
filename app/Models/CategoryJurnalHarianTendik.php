<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryJurnalHarianTendik extends Model
{
    protected $table = 'category_jurnal_harian_tendik';

    protected $primaryKey = 'id_category_jh_tendik';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];

    public function category_kelompok_jurnal_harian_tendik()
    {
        return $this->hasMany(CategoryKelompokJurnalHarianTendik::class, 'id_category_jh_tendik', 'id_category_jh_tendik');
    }
    public function unit_kerja()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }
}
