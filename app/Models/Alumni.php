<?php

namespace App\Models;

use App\Models\CalonSiswaBaru;
use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumni extends Model
{
    use SoftDeletes;
    use BaseModelTraits;

    protected $table = 'alumni';
    protected $primaryKey = 'id_alumni';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni',
        'id_c_siswa',
        'tahun_lulus',
        'status',
        'url_medsos',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function calon_siswa()
    {
        return $this->belongsTo(CalonSiswaBaru::class, 'id_c_siswa');
    }

    public function bekerja()
    {
        return $this->hasOne(AlumniBekerja::class, 'id_alumni');
    }

    public function kuliah()
    {
        return $this->hasOne(AlumniKuliah::class, 'id_alumni');
    }

    public function usaha()
    {
        return $this->hasOne(AlumniWirausaha::class, 'id_alumni');
    }

    public function menunggu()
    {
        return $this->hasOne(AlumniMenunggu::class, 'id_alumni');
    }
    public function smp()
    {
        return $this->hasOne(AlumniSmp::class, 'id_alumni');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
