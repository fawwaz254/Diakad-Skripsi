<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KelompokSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'kelompok_sisipan';

    protected $primaryKey = 'id_kelompok_sisipan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_kelompok_sisipan',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function sub_kelompok_sisipan()
    {
        return $this->hasMany(SubKelompokSisipan::class, 'id_kelompok_sisipan');
    }


    public function mata_pelajaran_sisipan()
    {
        return $this->hasMany(MataPelajaranSisipan::class, 'id_kelompok_sisipan')->orderBy('urutan', 'asc')->orderBy('jenis', 'asc');
    }
}
