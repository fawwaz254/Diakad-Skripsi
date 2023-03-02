<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WaliMurid
 */
class WaliMurid extends Model
{
    use SoftDeletes;

    protected $table = 'wali_murid';

    protected $primaryKey = 'id_wali_murid';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_pengguna',
        'nm_wali_murid',
        'nomor_hp_wali_murid',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_wali_murid', 'id_wali_murid');
    }
}
