<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BeasiswaSiswa
 */
class BeasiswaSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'beasiswa_siswa';

    protected $primaryKey = 'id_beasiswa_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'jenis_beasiswa_siswa',
        'keterangan_beasiswa_siswa',
        'tahun_mulai_beasiswa_siswa',
        'tahun_selesai_beasiswa_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}