<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKomponenKpi extends Model
{
    protected $table = 'nilai_komponen_kpi';

    protected $primaryKey = 'id_nilai_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kpi',
        'id_komponen',
        'id_siswa',
        'nilai_komponen',
        'deskripsi_nilai',
    ];

    protected $guarded = [];

    public function komponenkpi()
    {
        return $this->belongsTo(KomponenKpi::class, 'id_komponen_kpi');
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
