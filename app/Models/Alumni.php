<?php

namespace App\Models;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumni extends Model
{
    use SoftDeletes;

    protected $table = 'alumni';
    protected $primaryKey = 'id_alumni';
	public $timestamps = true;

    protected $fillable = [
        'id_siswa',
        'tahun_lulus',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
