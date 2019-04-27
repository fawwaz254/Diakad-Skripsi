<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PeriodeWisuda
 */
class PeriodeWisuda extends Model
{
    use SoftDeletes;

    protected $table = 'periode_wisuda';

    protected $primaryKey = 'id_periode_wisuda';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_wisuda',
        'id_semester',
        'nm_periode_wisuda',
        'besar_biaya',
        'tgl_bayar_mulai',
        'tgl_bayar_selesai',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}