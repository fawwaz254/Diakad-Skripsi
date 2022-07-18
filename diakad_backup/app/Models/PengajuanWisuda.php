<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengajuanWisuda
 */
class PengajuanWisuda extends Model
{
    use SoftDeletes;

    protected $table = 'pengajuan_wisuda';

    protected $primaryKey = 'id_pengajuan_wisuda';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_periode_wisuda',
        'status_biodata',
        'status_lab',
        'status_perpus',
        'status_ijasah',
        'nomor_sk_kelulusan',
        'tgl_sk_kelulusan',
        'nomor_ijasah',
        'tgl_kelulusan',
        'ipk',
        'tgl_pengajuan_wisuda',
        'status_wisuda',
        'keterangan_batal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function periode_wisuda(){
        return $this->belongsTo('App\Models\PeriodeWisuda', 'id_periode_wisuda');
    }

    public function siswa(){
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

}