<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporRoute
 */
class RaporRoute extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_route';

    protected $primaryKey = 'id_rapor_route';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_rapor_route',
        'path_rapor_route',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}