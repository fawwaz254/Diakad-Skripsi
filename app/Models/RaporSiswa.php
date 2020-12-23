<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporSiswa
 */
class RaporSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_siswa';

    protected $primaryKey = 'id_rapor_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_semester',
        'id_rapor_deskripsi',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_tanpa_keterangan',
        'id_prestasi_siswa',
        'deskripsi_catatan_wali_kelas',
        'nilai_kkm',
        'nilai_angka',
        'nilai_huruf',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function siswa(){
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    
    public function deskripsi(){
        return $this->belongsTo(RaporDeskripsi::class, 'id_rapor_deskripsi');
    }
    
    public function semester(){
        return $this->belongsTo(Semester::class, 'id_semester');
    }


}