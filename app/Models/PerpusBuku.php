<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerpusBuku
 */
class PerpusBuku extends Model
{
    use SoftDeletes;

    protected $table = 'perpus_buku';

    protected $primaryKey = 'id_perpus_buku';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_perpus_almari',
        'id_perpus_subkategori_buku',
        'kode_perpus_buku',
        'nm_perpus_buku',
        'tahun_perpus_buku',
        'judul_perpus_buku',
        'kota_penerbit_perpus_buku',
        'nm_penerbit_perpus_buku',
        'jumlah_perpus_buku',
        'is_upload_cover',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}