<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporSubkelompokMp
 */
class RaporSubkelompokMp extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_subkelompok_mp';

    protected $primaryKey = 'id_rapor_subkelompok_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_rapor_kelompok_mp',
        'id_mata_pelajaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}