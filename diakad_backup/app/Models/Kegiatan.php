<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Kegiatan
 */
class Kegiatan extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan';

    protected $primaryKey = 'id_kegiatan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_kegiatan',
        'deskripsi_kegiatan',
        'kode_kegiatan',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}