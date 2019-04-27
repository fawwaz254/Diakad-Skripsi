<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Kelas
 */
class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelas';

    protected $primaryKey = 'id_kelas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_jurusan',
        'nm_kelas',
        'tingkat',
        'keterangan_kelas',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}