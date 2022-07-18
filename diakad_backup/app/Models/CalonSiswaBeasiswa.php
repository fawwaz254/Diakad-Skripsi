<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaBeasiswa
 */
class CalonSiswaBeasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_beasiswa';

    protected $primaryKey = 'id_c_siswa_beasiswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_c_siswa',
        'jenis_beasiswa_c_siswa',
        'keterangan_beasiswa_c_siswa',
        'tahun_mulai_beasiswa_c_siswa',
        'tahun_selesai_beasiswa_c_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}