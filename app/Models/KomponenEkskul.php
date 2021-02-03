<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KomponenEkskul
 */
class KomponenEkskul extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_ekskul';

    protected $primaryKey = 'id_komponen_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ekskul',
        'id_semester',
        'nm_komponen_ekskul',
        'persentase_komponen_ekskul',
        'urutan_komponen_ekskul',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'id_ekskul');
    }
    
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }


}