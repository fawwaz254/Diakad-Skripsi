<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiMpPelanggaran
 */
class PresensiMpPelanggaran extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_mp_pelanggaran';

    protected $primaryKey = 'id_presensi_mp_pelanggaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_presensi_mp',
        'id_siswa',
        'catatan_pelanggaran',
        'is_sudah_tindakan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}