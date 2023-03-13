<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UjianMpRuangan
 */
class UjianMpRuangan extends Model
{
    use SoftDeletes;

    protected $table = 'ujian_mp_ruangan';

    protected $primaryKey = 'id_ujian_mp_ruangan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_ujian_mp',
        'id_ruangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function ruangan()
    {
        return $this->belongsTo('App\Models\Ruangan', 'id_ruangan');
    }
}
