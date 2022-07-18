<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengambilanMp
 */
class PengambilanMp extends Model
{
    use SoftDeletes;

    protected $table = 'pengambilan_mp';

    protected $primaryKey = 'id_pengambilan_mp';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'id_siswa',
        'id_semester',
        'status_apv_pengambilan_mp',
        'nilai_angka',
        'nilai_huruf',
        'persentase_presensi',
        'nilai_bobot',
        'nilai_angka_rapor',
        'nilai_huruf_rapor',
        'nilai_angka_keterampilan',
        'nilai_huruf_keterampilan',
        'is_tampil',
        'is_transfer',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function nilai_mp()
    {
        return $this->hasMany('App\Models\NilaiMp', 'id_pengambilan_mp', 'id_pengambilan_mp');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }

    public function kelas_mp()
    {
        return $this->belongsTo('App\Models\KelasMp', 'id_kelas_mp');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
}
