<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporDeskripsi
 */
class RaporDeskripsi extends Model
{
    use SoftDeletes;

    const KEPUTUSAN = [
        'Tinggal Kelas',
        'Naik Kelas',
        'Lulus'
    ];

    protected $table = 'rapor_deskripsi';

    protected $primaryKey = 'id_rapor_deskripsi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_rapor_subkategori',
        'id_rapor_kelompok_mp',
        'id_rapor_subkelompok_mp',
        'id_ekstrakurikuler',
        'predikat_rapor_deskripsi',
        'deskripsi_rapor',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function subkategori(){
        return $this->belongsTo(RaporSubkategori::class, 'id_rapor_subkategori');
    
    }
    public function kelompok(){
        return $this->belongsTo(RaporKelompokMp::class, 'id_rapor_kelompok_mp');
    }
    
    public function subkelompok(){
        return $this->belongsTo(RaporSubkelompokMp::class, 'id_rapor_subkelompok_mp');
    }

    public function ekstrakurikuler(){
        return $this->belongsTo(Ekskul::class, 'id_ekstrakurikuler');
    }


}