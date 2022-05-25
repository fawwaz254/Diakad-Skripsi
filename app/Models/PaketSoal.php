<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketSoal extends Model
{
    use SoftDeletes;

    protected $table = 'paket_soal';

    protected $primaryKey = 'id_paket_soal';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas',
        'text',
        'nilai',
        'waktu_mulai',
        'waktu_selesai',
        'waktu_pengerjaan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function kelas(){
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }

    public function detail_paket_soal(){
        return $this->hasMany('App\Models\DetailPaketSoal', 'id_paket_soal');
    }
  
}
