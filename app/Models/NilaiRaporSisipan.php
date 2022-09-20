<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiRaporSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_rapor_sisipan';

    protected $primaryKey = 'id_nilai_rapor_sisipan';

	public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];
    public function siswa(){
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    public function rapor_sisipan(){
        return $this->belongsTo(RaporSisipan::class, 'id_rapor_sisipan');
    }

    public function komponen_nilai(){
        return $this->hasMany(KomponenNilaiRaporSisipan::class, 'id_komponen_nilai', 'id_komponen_nilai');
    }
}
