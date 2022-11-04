<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubRaporSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'sub_rapor_sisipan';

    protected $primaryKey = 'id_sub_rapor_sisipan';

	public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];

    public function sub_rapor_sisipan_mp()
    {
        return $this->hasMany(SubRaporSisipanMP::class, 'id_sub_rapor_sisipan');
    }

    public function jenis_mata_pelajaran()
    {
        return $this->belongsTo(JenisMataPelajaran::class, 'id_jenis_mata_pelajaran');
    }
 
}
