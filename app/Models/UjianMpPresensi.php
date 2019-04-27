<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UjianMpPresensi
 */
class UjianMpPresensi extends Model
{
    use SoftDeletes;

    protected $table = 'ujian_mp_presensi';

    protected $primaryKey = 'id_ujian_mp_presensi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ujian_mp',
        'id_siswa',
        'kehadiran',
        'alasan',
        'tgl_ujian_mp_presensi',
        'jam_mulai_presensi',
        'jam_selesai_presensi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}