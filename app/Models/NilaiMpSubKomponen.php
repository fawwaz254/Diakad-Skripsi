<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiMpSubKomponen extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_mp_subkomponen';

    protected $primaryKey = 'id_nilai_mp_subkomponen';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengambilan_mp',
        'id_subkomponen_mp',
        'besar_nilai_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
