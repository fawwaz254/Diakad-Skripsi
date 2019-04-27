<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrestasiSiswa
 */
class PrestasiSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'prestasi_siswa';

    protected $primaryKey = 'id_prestasi_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_semester',
        'id_tingkat_prestasi_siswa',
        'id_guru_pendamping',
        'id_ekskul',
        'jenis_prestasi_siswa',
        'nm_prestasi_siswa',
        'lokasi_prestasi_siswa',
        'penyelenggara_prestasi_siswa',
        'peringkat_prestasi_siswa',
        'tgl_prestasi_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}