<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RpbSarprasSupplier
 */
class RpbSarprasSupplier extends Model
{
    use SoftDeletes;

    protected $table = 'rpb_sarpras_supplier';

    protected $primaryKey = 'id_rpb_sarpras_supplier';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_rpb_sarpras',
        'id_supplier',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}