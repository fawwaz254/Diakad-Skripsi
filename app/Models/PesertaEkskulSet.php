<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PesertaEkskulSet
 */
class PesertaEkskulSet extends Model
{
    use SoftDeletes;

    protected $table = 'peserta_ekskul_set';

    protected $primaryKey = 'id_peserta_ekskul_set';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_ekskul',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}