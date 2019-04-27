<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipPemilik
 */
class ArsipPemilik extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_pemilik';

    protected $primaryKey = 'id_arsip_pemilik';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_unit_kerja',
        'nm_arsip_pemilik',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}