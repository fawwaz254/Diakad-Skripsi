<?php

namespace App\Models;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniIdle extends Model
{
    use SoftDeletes;

    protected $table = 'alumni_idle';
    protected $primaryKey = 'id_alumni_idle';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_idle',
        'id_alumni',
        'idle_status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni', 'id_alumni');
    }
}
