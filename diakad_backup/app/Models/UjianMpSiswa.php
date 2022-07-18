<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UjianMpSiswa
 */
class UjianMpSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'ujian_mp_siswa';

    protected $primaryKey = 'id_ujian_mp_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ujian_mp_soal',
        'id_ujian_mp_presensi',
        'jawaban_pilihan',
        'jawaban_esai',
        'tgl_entry_jawaban',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}