<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NilaiMagang
 */
class NilaiMagang extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_magang';

    protected $primaryKey = 'id_nilai_magang';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengambilan_magang',
        'id_komponen_magang',
        'besar_nilai_magang',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}