<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Siswa
 */
class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_pengguna',
        'id_c_siswa',
        'id_kelompok_biaya',
        'id_kelas',
        'id_wali_murid',
        'is_orang_tua',
        'nis_siswa',
        'nisn_siswa',
        'thn_masuk_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function pengguna(){
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna');
    }

    public function tagihan_biaya(){
        return $this->hasMany('App\Models\TagihanBiaya', 'id_siswa');
    }
}