<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipLoker
 */
class ArsipLoker extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_loker';

    protected $primaryKey = 'id_arsip_loker';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_unit_kerja',
        'nm_arsip_loker',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}