<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BukuIndukRoute
 */
class BukuIndukRoute extends Model
{
    use SoftDeletes;

    protected $table = 'buku_induk_route';

    protected $primaryKey = 'id_buku_induk_route';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_buku_induk_route',
        'path_buku_induk_route',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}