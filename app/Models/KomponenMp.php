<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KomponenMp
 */
class KomponenMp extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_mp';

    protected $primaryKey = 'id_komponen_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'nm_komponen_mp',
        'persentase_komponen_mp',
        'urutan_komponen_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}