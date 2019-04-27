<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengeluaranBiayaSubkategori
 */
class PengeluaranBiayaSubkategori extends Model
{
    use SoftDeletes;

    protected $table = 'pengeluaran_biaya_subkategori';

    protected $primaryKey = 'id_pengeluaran_biaya_subkategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengeluaran_biaya_kategori',
        'nm_pengeluaran_biaya_subkategori',
        'keterangan_pengeluaran_biaya_subkategori',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}