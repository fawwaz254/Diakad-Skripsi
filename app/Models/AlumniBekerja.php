<?php

namespace App\Models;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniBekerja extends Model
{
    use SoftDeletes;

    protected $table = 'alumni_bekerja';
    protected $primaryKey = 'id_alumni_bekerja';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_bekerja',
        'id_alumni',
        'nm_instansi',
        'alamat_instansi',
        'kontak_instansi',
        'bidang_usaha_instansi',
        'tahun_masuk_instansi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }

}
