<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengeluaranBiayaKategori
 */
class PengeluaranBiayaKategori extends Model
{
    use SoftDeletes;

    protected $table = 'pengeluaran_biaya_kategori';

    protected $primaryKey = 'id_pengeluaran_biaya_kategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_pengeluaran_biaya_kategori',
        'keterangan_pengeluaran_biaya_kategori',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}