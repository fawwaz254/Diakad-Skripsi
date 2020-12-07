<?php

namespace App\Models;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniUniversity extends Model
{
    use SoftDeletes;

    protected $table = 'alumni_university';
    protected $primaryKey = 'id_alumni_university';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_university',
        'id_alumni',
        'nm_perguruan',
        'jurusan',
        'prodi',
        'jenjang',
        'tahun_masuk',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni', 'id_alumni');
    }
}
