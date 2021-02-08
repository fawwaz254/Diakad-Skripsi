<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrestasiSiswa
 */
class KegiatanSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_siswa';

    protected $primaryKey = 'id_kegiatan_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'id_semester',
        'tgl_kegiatan_siswa',
        'nm_kegiatan_siswa',
        'id_tingkat_prestasi_siswa',
        'nm_kegiatan_scan_sertif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}