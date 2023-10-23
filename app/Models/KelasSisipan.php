<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KelasSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'kelas_sisipan';

    protected $primaryKey = 'id_kelas_sisipan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_mata_pelajaran_sisipan',
        'id_kelas',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function mata_pelajaran_sisipan()
    {
        return $this->belongsTo(MataPelajaranSisipan::class, 'id_mata_pelajaran_sisipan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
