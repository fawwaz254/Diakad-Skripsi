<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UjianMpSoalFile
 */
class UjianMpSoalFile extends Model
{
    use SoftDeletes;

    protected $table = 'ujian_mp_soal_file';

    protected $primaryKey = 'id_ujian_mp_soal_file';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ujian_mp_soal',
        'nm_soal_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}