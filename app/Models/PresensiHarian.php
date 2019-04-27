<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiHarian
 */
class PresensiHarian extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_harian';

    protected $primaryKey = 'id_presensi_harian';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas',
        'id_semester',
        'id_jadwal_hari',
        'id_siswa_entry',
        'id_guru_entry',
        'tgl_entry',
        'persentase_presensi_harian',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}