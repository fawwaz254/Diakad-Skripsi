<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RpbSarpras
 */
class RpbSarpras extends Model
{
    use SoftDeletes;

    protected $table = 'rpb_sarpras';

    protected $primaryKey = 'id_rpb_sarpras';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester',
        'id_unit_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}