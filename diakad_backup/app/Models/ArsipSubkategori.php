<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipSubkategori
 */
class ArsipSubkategori extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_subkategori';

    protected $primaryKey = 'id_arsip_subkategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_arsip_kategori',
        'nm_arsip_subkategori',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}