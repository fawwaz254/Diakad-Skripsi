<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Jurusan
 */
class Jurusan extends Model
{
    use SoftDeletes;

    protected $table = 'jurusan';

    protected $primaryKey = 'id_jurusan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_jurusan',
        'kode_jurusan',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jurusan');
    }
}
