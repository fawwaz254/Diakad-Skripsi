<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniWorkplace extends Model
{
    use SoftDeletes;

    protected $table = 'alumni_workplace';
    protected $primaryKey = 'id_alumni_workplace';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_workplace',
        'id_alumni',
        'nm_instansi',
        'alamat',
        'kontak',
        'bidang_usaha',
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
