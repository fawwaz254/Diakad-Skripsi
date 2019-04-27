<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporKategori
 */
class RaporKategori extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_kategori';

    protected $primaryKey = 'id_rapor_kategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'kode_rapor_kategori',
        'nm_rapor_kategori',
        'urutan_rapor_kategori',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}