<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GuruKpi extends Model
{
    use SoftDeletes;

    protected $table = 'guru_kpi';

    protected $primaryKey = 'id_guru_kpi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

}
