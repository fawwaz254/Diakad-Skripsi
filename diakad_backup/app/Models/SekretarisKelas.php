<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SekretarisKelas
 */
class SekretarisKelas extends Model
{
    use SoftDeletes;

    protected $table = 'sekretaris_kelas';

    protected $primaryKey = 'id_sekretaris_kelas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas',
        'id_siswa',
        'id_semester',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}