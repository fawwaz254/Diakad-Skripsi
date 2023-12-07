<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class MataPelajaranRapor extends Model
{
    use SoftDeletes;

    protected $table = 'mata_pelajaran_rapor';

    protected $primaryKey = 'id_mata_pelajaran_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_mapel_rapor',
        'id_sub_kelompok_mapel_rapor',
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
    public function kelompok_mapel_rapor()
    {
        return $this->belongsTo(KelompokMapelRapor::class, 'id_kelompok_mapel_rapor');
    }

    public function sub_kelompok_mapel_rapor()
    {
        return $this->belongsTo(SubKelompokMapelRapor::class, 'id_sub_kelompok_mapel_rapor');
    }

    public function kelas_rapor()
    {
        return $this->hasMany(KelasRapor::class, 'id_mata_pelajaran_rapor', 'id_mata_pelajaran_rapor');
    }
}
