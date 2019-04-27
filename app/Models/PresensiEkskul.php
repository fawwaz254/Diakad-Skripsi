<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiEkskul
 */
class PresensiEkskul extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_ekskul';

    protected $primaryKey = 'id_presensi_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ekskul',
        'id_semester',
        'pertemuan_ke',
        'materi_ekskul',
        'waktu_mulai',
        'waktu_selesai',
        'tgl_entry',
        'persentase_presensi_ekskul',
        'keterangan_presensi_ekskul',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}