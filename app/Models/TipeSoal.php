<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ClassTipeSoal 
 */
class TipeSoal extends Model
{
    use SoftDeletes;

    protected $table = 'tipe_soal';

    protected $primaryKey = 'id_tipe_soal';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_tipe_soal',
        'nm_tipe_soal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
