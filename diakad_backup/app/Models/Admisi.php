<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Admisi
 */
class Admisi extends Model
{
    use SoftDeletes;

    protected $table = 'admisi';

    protected $primaryKey = 'id_admisi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_admisi',
        'id_siswa',
        'id_semester',
        'id_status_pengguna',
        'id_jalur',
        'ips',
        'ipk',
        'id_pengajuan_wisuda',
        'tgl_keluar',
        'alasan_keluar',
        'kode_sekolah_asal',
        'nm_sekolah_asal',
        'keterangan_admisi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}