<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubKelompokSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'sub_kelompok_sisipan';

    protected $primaryKey = 'id_sub_kelompok_sisipan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_sisipan',
        'nm_sub_kelompok_sisipan',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function mata_pelajaran_sisipan()
    {
        return $this->hasMany(MataPelajaranSisipan::class, 'id_sub_kelompok_sisipan');
    }
    public function kelompok_sisipan()
    {
        return $this->belongsTo(KelompokSisipan::class, 'id_kelompok_sisipan');
    }
}
