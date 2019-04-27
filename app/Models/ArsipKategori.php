<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipKategori
 */
class ArsipKategori extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_kategori';

    protected $primaryKey = 'id_arsip_kategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_arsip_kategori',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}