<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Modul
 */
class Modul extends Model
{
    use SoftDeletes;

    protected $table = 'modul';

    protected $primaryKey = 'id_modul';

	public $timestamps = true;

    protected $fillable = [
        'id_role',
        'nm_modul',
        'route',
        'page',
        'urutan',
        'akses',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_modul')->where('akses', 1)->orderBy('urutan', 'asc');
    }

}