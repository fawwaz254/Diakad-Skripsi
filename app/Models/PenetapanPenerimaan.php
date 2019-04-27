<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PenetapanPenerimaan
 */
class PenetapanPenerimaan extends Model
{
    use SoftDeletes;

    protected $table = 'penetapan_penerimaan';

    protected $primaryKey = 'id_penetapan_penerimaan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_penetapan',
        'id_penerimaan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}