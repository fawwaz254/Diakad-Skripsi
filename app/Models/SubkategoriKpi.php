<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubkategoriKpi extends Model
{
    protected $table = 'subkategori_kpi';

    protected $primaryKey = 'id_subkategori_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kategori_kpi',
        'nm_subkategori',
    ];

    protected $guarded = [];

    public function komponenkpi()
    {
        return $this->hasMany(KomponenKpi::class, 'id_subkategori_kpi');
    }

    public function kategorikpi()
    {
        return $this->belongsTo(KategoriKpi::class, 'id_kategori_kpi');
    }

}
