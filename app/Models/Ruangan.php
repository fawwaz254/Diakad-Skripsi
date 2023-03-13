<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ruangan
 */
class Ruangan extends Model
{
    use SoftDeletes;

    protected $table = 'ruangan';

    protected $primaryKey = 'id_ruangan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_jenis_ruangan',
        'id_pemilik_sarpras',
        'id_gedung',
        'id_kelas',
        'nm_ruangan',
        'panjang_ruangan',
        'lebar_ruangan',
        'kapasitas_ruangan',
        'kapasitas_ujian',
        'deskripsi_ruangan',
        'is_perpustakaan',
        'nomor_registrasi_perpustakaan',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function gedung()
    {
        return $this->belongsTo('App\Models\Gedung', 'id_gedung');
    }
}
