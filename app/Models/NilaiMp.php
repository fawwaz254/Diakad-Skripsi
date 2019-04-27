<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NilaiMp
 */
class NilaiMp extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_mp';

    protected $primaryKey = 'id_nilai_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengambilan_mp',
        'id_komponen_mp',
        'besar_nilai_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}