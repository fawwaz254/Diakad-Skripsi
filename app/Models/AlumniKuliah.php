<?php

namespace App\Models;

use App\Models\Alumni;
use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniKuliah extends Model
{
    use SoftDeletes;
    use BaseModelTraits;

    protected $table = 'alumni_kuliah';
    protected $primaryKey = 'id_alumni_kuliah';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_kuliah',
        'id_alumni',
        'nm_perguruan',
        'alamat_perguruan',
        'jurusan',
        'prodi',
        'jenjang',
        'tahun_masuk_perguruan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }
}
