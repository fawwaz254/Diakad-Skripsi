<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PemasukanBiayaSubkategori
 */
class PemasukanBiayaSubkategori extends Model
{
    use SoftDeletes;

    protected $table = 'pemasukan_biaya_subkategori';

    protected $primaryKey = 'id_pemasukan_biaya_subkategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pemasukan_biaya_kategori',
        'nm_pemasukan_biaya_subkategori',
        'keterangan_pemasukan_biaya_subkategori',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}