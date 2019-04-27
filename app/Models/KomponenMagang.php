<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KomponenMagang
 */
class KomponenMagang extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_magang';

    protected $primaryKey = 'id_komponen_magang';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_periode_magang',
        'nm_komponen_magang',
        'persentase_komponen_magang',
        'urutan_komponen_magang',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}