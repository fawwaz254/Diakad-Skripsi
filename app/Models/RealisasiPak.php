<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RealisasiPak
 */
class RealisasiPak extends Model
{
    use SoftDeletes;

    protected $table = 'realisasi_pak';

    protected $primaryKey = 'id_realisasi_pak';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_realisasi_pak',
        'id_pak',
        'id_unit_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}