<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKerjaHarianMGMP extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_kerja_harian_mgmp';

    protected $primaryKey = 'id_laporan_kerja_harian_mgmp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
