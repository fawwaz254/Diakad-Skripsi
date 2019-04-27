<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiMp
 */
class PresensiMp extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_mp';

    protected $primaryKey = 'id_presensi_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'id_jadwal_kelas_mp',
        'pertemuan_ke',
        'uraian_materi',
        'waktu_mulai',
        'waktu_selesai',
        'tgl_presensi',
        'id_guru_pengganti',
        'alasan_tidak_hadir',
        'tgl_entry',
        'persentase_presensi_mp',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}