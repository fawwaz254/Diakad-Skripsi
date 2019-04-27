<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisKepegawaian
 */
class JenisKepegawaian extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_kepegawaian';

    protected $primaryKey = 'id_jenis_kepegawaian';

	public $timestamps = true;

    protected $fillable = [
        'kode_jenis_kepegawaian',
        'nm_jenis_kepegawaian',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}