<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rapb
 */
class Rapb extends Model
{
    use SoftDeletes;

    protected $table = 'rapb';

    protected $primaryKey = 'id_rapb';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_mulai',
        'id_semester_selesai',
        'id_subkategori_rapb',
        'id_unit_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function subkategori()
    {
        return $this->belongsTo(SubkategoriRapb::class, 'id_subkategori_rapb');
    }

    public function scopeIsInputByPengguna($query, $id_pengguna){
        return $query->where('rapb.created_by', $id_pengguna);
    }


}