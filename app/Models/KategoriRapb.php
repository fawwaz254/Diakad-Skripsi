<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KategoriRapb
 */
class KategoriRapb extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_rapb';

    protected $primaryKey = 'id_kategori_rapb';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'kode_kategori_rapb',
        'nm_kategori_rapb',
        'deskripsi_kategori_rapb',
        'tipe_kategori_rapb',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}