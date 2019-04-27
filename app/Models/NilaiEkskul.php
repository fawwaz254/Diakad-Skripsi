<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NilaiEkskul
 */
class NilaiEkskul extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_ekskul';

    protected $primaryKey = 'id_nilai_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengambilan_ekskul',
        'id_komponen_ekskul',
        'besar_nilai_ekskul',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}