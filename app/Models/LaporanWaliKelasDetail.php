<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Biaya
 */
class LaporanWaliKelasDetail extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_wali_kelas_detail';

    protected $primaryKey = 'id_laporan_wali_kelas_detail';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    public function guru(){
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }

}