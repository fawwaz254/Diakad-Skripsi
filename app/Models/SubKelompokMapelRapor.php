<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubKelompokMapelRapor extends Model
{
    use SoftDeletes;

    protected $table = 'sub_kelompok_mapel_rapor';

    protected $primaryKey = 'id_sub_kelompok_mapel_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_mapel_rapor',
        'nm_sub_kelompok_mapel_rapor',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function mata_pelajaran_rapor()
    {
        return $this->hasMany(MataPelajaranRapor::class, 'id_sub_kelompok_mapel_rapor');
    }
    public function kelompok_mapel_rapor()
    {
        return $this->belongsTo(KelompokMapelRapor::class, 'id_kelompok_mapel_rapor');
    }
}
