<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BukuAlat
 */
class BukuAlat extends Model
{
    use SoftDeletes;

    protected $table = 'buku_alat';

    protected $primaryKey = 'id_buku_alat';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_jenis_buku_alat',
        'nm_buku_alat',
        'tingkat_pendidikan_buku_alat',
        'id_mata_pelajaran',
        'kode_buku_alat',
        'tgl_pembelian',
        'jumlah_buku_alat',
        'jumlah_kondisi_baik',
        'jumlah_kondisi_rusak',
        'keterangan_buku_alat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}