<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerpusBukuFile
 */
class PerpusBukuFile extends Model
{
    use SoftDeletes;

    protected $table = 'perpus_buku_file';

    protected $primaryKey = 'id_perpus_buku_file';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_perpus_buku',
        'nm_perpus_buku_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}