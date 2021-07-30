<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Biaya
 */
class LaporanKerjaHarian extends Model
{
    use SoftDeletes;

    protected $table = 'laporan_kerja_harian';

    protected $primaryKey = 'id_laporan_kerja_harian';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

}