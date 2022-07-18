<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PemasukanBiayaKategori
 */
class PemasukanBiayaKategori extends Model
{
    use SoftDeletes;

    protected $table = 'pemasukan_biaya_kategori';

    protected $primaryKey = 'id_pemasukan_biaya_kategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_pemasukan_biaya_kategori',
        'keterangan_pemasukan_biaya_kategori',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}