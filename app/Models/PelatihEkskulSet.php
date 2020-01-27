<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PelatihEkskulSet
 */
class PelatihEkskulSet extends Model
{
    use SoftDeletes;

    protected $table = 'pelatih_ekskul_set';

    protected $primaryKey = 'id_pelatih_ekskul_set';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pelatih_ekskul',
        'id_ekskul',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function ekskul()
    {
        return $this->belongsTo('App\Models\Ekskul', 'id_ekskul');
    }
}
