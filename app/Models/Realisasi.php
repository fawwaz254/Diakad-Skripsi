<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Realisasi
 */
class Realisasi extends Model
{
    use SoftDeletes;

    protected $table = 'realisasi';

    protected $primaryKey = 'id_realisasi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_realisasi',
        'id_rapb',
        'id_unit_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function rapb()
    {
        return $this->belongsTo(Rapb::class, 'id_rapb');
    }

    public function scopeIsInputByPengguna($query, $id_pengguna){
        return $query->where('realisasi.created_by', $id_pengguna);
    }
}