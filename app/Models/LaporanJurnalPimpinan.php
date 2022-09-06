<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanJurnalPimpinan extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_jurnal_pimpinan';

    protected $primaryKey = 'id_laporan_jurpin';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    // public function role()
    // {
    //     return $this->belongsTo(Role::class, 'id_role');
    // }
    // public function category_jurnal_harian_tendik()
    // {
    //     return  $this->belongsTo(CategoryJurnalHarianTendik::class,'id_category_jh_tendik');
    // }
    public function pengguna(){
        return  $this->belongsTo(Pengguna::class,'id_pengguna');
    }
}
