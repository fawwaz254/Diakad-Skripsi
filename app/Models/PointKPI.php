<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PointKPI extends Model
{
    use SoftDeletes;

    protected $table = 'point_kpi';

    protected $primaryKey = 'id_point_kpi';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_kpi',
        'id_semester',
        'nm_point_kpi',
        'tingkat_kelas',
        'id_semester',
        'urutan',
        'deskripsi',
        'jenis',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    protected $casts = [
        'deskripsi' => 'json',
    ];

    public function kelompok_kpi()
    {
        return $this->belongsTo(KelompokKPI::class, 'id_kelompok_kpi');
    }
}
