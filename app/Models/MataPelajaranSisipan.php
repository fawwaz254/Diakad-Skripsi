<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MataPelajaranSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'mata_pelajaran_sisipan';

    protected $primaryKey = 'id_mata_pelajaran_sisipan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_sisipan',
        'id_sub_kelompok_sisipan',
        'id_mata_pelajaran',
        'urutan',
        'jenis',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }
    public function kelompok_sisipan()
    {
        return $this->belongsTo(KelompokSisipan::class, 'id_kelompok_sisipan');
    }

    public function sub_kelompok_sisipan()
    {
        return $this->belongsTo(SubKelompokSisipan::class, 'id_sub_kelompok_sisipan');
    }

    public function kelas_sisipan()
    {
        return $this->hasMany(KelasSisipan::class, 'id_mata_pelajaran_sisipan', 'id_mata_pelajaran_sisipan');
    }
}
