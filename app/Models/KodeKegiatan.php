<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KodeKegiatan
 */
class KodeKegiatan extends Model
{
    use SoftDeletes;

    protected $table = 'kode_kegiatan';

    protected $primaryKey = 'kode_kegiatan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}