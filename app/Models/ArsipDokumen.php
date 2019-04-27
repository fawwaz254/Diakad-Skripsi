<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArsipDokuman
 */
class ArsipDokumen extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_dokumen';

    protected $primaryKey = 'id_arsip_dokumen';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_arsip_loker',
        'id_arsip_pemilik',
        'id_arsip_subkategori',
        'id_unit_kerja',
        'kode_katalog',
        'nm_arsip_dokumen',
        'nomor_arsip_dokumen',
        'jumlah_halaman',
        'tgl_penyusunan',
        'contact_person',
        'is_upload',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}