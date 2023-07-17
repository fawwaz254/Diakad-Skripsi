<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKpi extends Model
{
    protected $table = 'kategori_kpi';

    protected $primaryKey = 'id_kategori_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_kategori',
        'tingkat',
        'semester',
    ];

    protected $guarded = [];

    public function haskpi()
    {
        return $this->hasMany(HasKpi::class, 'id_kategori_kpi');
    }

    public function subkategorikpi()
    {
        return $this->hasMany(SubkategoriKpi::class, 'id_kategori_kpi');
    }
}
