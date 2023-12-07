<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasRapor extends Model
{
    use SoftDeletes;

    protected $table = 'kelas_rapor';

    protected $primaryKey = 'id_kelas_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_mata_pelajaran_rapor',
        'id_kelas',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function mata_pelajaran_rapor()
    {
        return $this->belongsTo(MataPelajaranRapor::class, 'id_mata_pelajaran_rapor');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
