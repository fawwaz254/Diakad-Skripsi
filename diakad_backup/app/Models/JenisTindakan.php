<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisTindakan
 */
class JenisTindakan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_tindakan';

    protected $primaryKey = 'id_jenis_tindakan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_jenis_tindakan',
        'keterangan_jenis_tindakan',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}