<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KelompokKPI extends Model
{
    use SoftDeletes;

    protected $table = 'kelompok_kpi';

    protected $primaryKey = 'id_kelompok_kpi';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_kelompok_kpi',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function point_kpi()
    {
        return $this->hasMany(PointKPI::class, 'id_kelompok_kpi');
    }
}
