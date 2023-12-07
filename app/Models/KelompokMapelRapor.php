<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KelompokMapelRapor extends Model
{
    use SoftDeletes;

    protected $table = 'kelompok_mapel_rapor';

    protected $primaryKey = 'id_kelompok_mapel_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_kelompok_mapel_rapor',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function sub_kelompok_mapel_rapor()
    {
        return $this->hasMany(SubKelompokMapelRapor::class, 'id_kelompok_mapel_rapor');
    }


    public function mata_pelajaran_rapor()
    {
        return $this->hasMany(MataPelajaranRapor::class, 'id_kelompok_mapel_rapor')->orderBy('urutan', 'asc')->orderBy('jenis', 'asc');
    }
}
