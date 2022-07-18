<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Menu
 */
class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

	public $timestamps = true;

    protected $fillable = [
        'id_modul',
        'nm_menu',
        'page',
        'urutan',
        'akses',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function modul(){
        return $this->belongsTo('App\Models\Modul', 'id_modul');
    }


}