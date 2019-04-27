<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerpusAlmari
 */
class PerpusAlmari extends Model
{
    use SoftDeletes;

    protected $table = 'perpus_almari';

    protected $primaryKey = 'id_perpus_almari';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ruangan',
        'kode_perpus_almari',
        'nm_perpus_almari',
        'deskripsi_perpus_almari',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}