<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TindakanPelanggaran
 */
class TindakanPelanggaran extends Model
{
    use SoftDeletes;

    protected $table = 'tindakan_pelanggaran';

    protected $primaryKey = 'id_tindakan_pelanggaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pelanggaran_siswa',
        'id_presensi_mp_pelanggaran',
        'id_jenis_tindakan',
        'id_semester',
        'catatan_tindakan_pelanggaran',
        'catatan_tindakan_pelanggaran_khusus',
        'tgl_tindakan_pelanggaran',
        'aktor_input_tindakan_pelanggaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}