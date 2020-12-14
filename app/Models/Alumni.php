<?php

namespace App\Models;

use App\Models\CalonSiswaBaru;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumni extends Model
{
    use SoftDeletes;

    protected $table = 'alumni';
    protected $primaryKey = 'id_alumni';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni',
        'id_c_siswa',
        'tahun_lulus',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function calon_siswa()
    {
        return $this->belongsTo(CalonSiswaBaru::class, 'id_c_siswa');
    }
}
