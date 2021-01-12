<?php

namespace App\Models;

use App\Models\Alumni;
use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniMenunggu extends Model
{
    use SoftDeletes;
    use BaseModelTraits;

    protected $table = 'alumni_menunggu';
    protected $primaryKey = 'id_alumni_menunggu';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_menunggu',
        'id_alumni',
        'status_menunggu',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }
}
