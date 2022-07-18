<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubKomponenMp extends Model
{
    use SoftDeletes;

    protected $table = 'subkomponen_mp';

    protected $primaryKey = 'id_subkomponen_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_komponen_mp',
        'kd_subkomponen_mp',
        'nm_subkomponen_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
