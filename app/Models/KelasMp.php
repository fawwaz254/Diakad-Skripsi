<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KelasMp
 */
class KelasMp extends Model
{
    use SoftDeletes;

    protected $table = 'kelas_mp';

    protected $primaryKey = 'id_kelas_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester',
        'id_kelas',
        'id_mata_pelajaran',
        'nm_kelas_mp',
        'jml_pertemuan_kelas_mp',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}