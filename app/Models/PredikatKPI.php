<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PredikatKPI extends Model
{
    use SoftDeletes;

    protected $table = 'predikat_kpi';

    protected $primaryKey = 'id_predikat_kpi';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_point_kpi',
        'id_kelas',
        'id_siswa',
        'predikat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function point_kpi()
    {
        return $this->belongsTo(PointKPI::class, 'id_point_kpi');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
