<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerpusKategoriBuku
 */
class PerpusKategoriBuku extends Model
{
    use SoftDeletes;

    protected $table = 'perpus_kategori_buku';

    protected $primaryKey = 'id_perpus_kategori_buku';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_perpus_kategori_buku',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}