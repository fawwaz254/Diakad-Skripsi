<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenKpi extends Model
{
    protected $table = 'komponen_kpi';

    protected $primaryKey = 'id_komponen_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_subkategori_kpi',
        'nm_komponen',
        'deskripsi_komponen',
    ];

    protected $guarded = [];

    public function subkategorikpi()
    {
        return $this->belongsTo(SubkategoriKpi::class, 'id_subkategori_kpi');
    }

    public function nilaikomponenkpi()
    {
        return $this->hasMany(NilaiKomponenKpi::class, 'id_komponen');
    }


}
