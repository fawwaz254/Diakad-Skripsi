<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporKelompokMp
 */
class RaporKelompokMp extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_kelompok_mp';

    protected $primaryKey = 'id_rapor_kelompok_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_rapor_kelompok',
        'id_rapor_kategori',
        'id_mata_pelajaran',
        'nm_rapor_kelompok_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}