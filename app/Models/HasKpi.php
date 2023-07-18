<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasKpi extends Model
{
    protected $table = 'has_kpi';

    protected $primaryKey = 'id_has_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kpi',
        'id_kategori_kpi',
    ];

    protected $guarded = [];

    public function kategorikpi()
    {
        return $this->belongsTo(KategoriKpi::class, 'id_kategori_kpi');
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'id_kpi');
    }
}
