<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PeraturanNilai
 */
class PeraturanNilai extends Model
{
    use SoftDeletes;

    protected $table = 'peraturan_nilai';

    protected $primaryKey = 'id_peraturan_nilai';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_standar_nilai',
        'nilai_kkm',
        'nilai_min_peraturan_nilai',
        'nilai_max_peraturan_nilai',
        'is_mata_pelajaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}