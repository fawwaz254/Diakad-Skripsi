<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Submenu
 */
class Submenu extends Model
{
    use SoftDeletes;

    protected $table = 'submenu';

    protected $primaryKey = 'id_submenu';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_menu',
        'nm_submenu',
        'page',
        'urutan',
        'akses',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}