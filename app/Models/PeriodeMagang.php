<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PeriodeMagang
 */
class PeriodeMagang extends Model
{
    use SoftDeletes;

    protected $table = 'periode_magang';

    protected $primaryKey = 'id_periode_magang';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_magang',
        'id_semester',
        'nm_periode_magang',
        'nomor_sk_periode_magang',
        'besar_biaya',
        'tgl_magang_mulai',
        'tgl_magang_selesai',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
}
