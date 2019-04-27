<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HomeVisit
 */
class HomeVisit extends Model
{
    use SoftDeletes;

    protected $table = 'home_visit';

    protected $primaryKey = 'id_home_visit';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester',
        'id_guru_wali_kelas',
        'id_siswa',
        'nomor_hp_wali_murid',
        'alamat_wali_murid',
        'rangkuman_home_visit',
        'is_berkas_lengkap',
        'id_guru_kesiswaan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}