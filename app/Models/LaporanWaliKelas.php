<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Biaya
 */
class LaporanWaliKelas extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_wali_kelas';

    protected $primaryKey = 'id_laporan_wali_kelas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

    public function bulan()
    {
        return $this->belongsTo(Bulan::class, 'id_bulan');
    }

    public function detail()
    {
        return $this->belongsTo(LaporanWaliKelasDetail::class, 'id_laporan_wali_kelas');
    }

}