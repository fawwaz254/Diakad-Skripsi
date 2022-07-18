<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InventarisRuangan
 */
class InventarisRuangan extends Model
{
    use SoftDeletes;

    protected $table = 'inventaris_ruangan';

    protected $primaryKey = 'id_inventaris_ruangan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ruangan',
        'nm_inventaris_ruangan',
        'kode_inventaris_ruangan',
        'tgl_pembelian',
        'jumlah_inventaris_ruangan',
        'jumlah_kondisi_baik',
        'jumlah_kondisi_rusak',
        'spesifikasi_inventaris_ruangan',
        'keterangan_inventaris_ruangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}