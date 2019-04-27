<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RuanganKelas
 */
class RuanganKelas extends Model
{
    use SoftDeletes;

    protected $table = 'ruangan_kelas';

    protected $primaryKey = 'id_ruangan_kelas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas',
        'id_ruangan',
        'id_semester',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}