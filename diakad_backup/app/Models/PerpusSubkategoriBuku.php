<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerpusSubkategoriBuku
 */
class PerpusSubkategoriBuku extends Model
{
    use SoftDeletes;

    protected $table = 'perpus_subkategori_buku';

    protected $primaryKey = 'id_perpus_subkategori_buku';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_perpus_kategori_buku',
        'nm_perpus_subkategori_buku',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}