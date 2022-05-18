<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventarisBergerak extends Model
{
    use SoftDeletes;

    protected $table = 'inventaris_bergerak';

    protected $primaryKey = 'id_inventaris_bergerak';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
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
