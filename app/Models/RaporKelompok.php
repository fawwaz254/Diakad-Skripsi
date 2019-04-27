<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporKelompok
 */
class RaporKelompok extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_kelompok';

    protected $primaryKey = 'id_rapor_kelompok';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_rapor_kelompok',
        'urutan_rapor_kelompok',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}