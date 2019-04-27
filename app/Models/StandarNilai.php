<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StandarNilai
 */
class StandarNilai extends Model
{
    use SoftDeletes;

    protected $table = 'standar_nilai';

    protected $primaryKey = 'id_standar_nilai';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_standar_nilai',
        'mutu_standar_nilai',
        'keterangan_standar_nilai',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}