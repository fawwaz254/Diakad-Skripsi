<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JalurSiswa
 */
class JalurSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'jalur_siswa';

    protected $primaryKey = 'id_jalur_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_jalur_siswa',
        'id_siswa',
        'id_jalur',
        'id_semester',
        'is_jalur_aktif',
        'id_admisi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}