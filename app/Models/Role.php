<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Role
 */
class Role extends Model
{
    use SoftDeletes;

    protected $table = 'role';

    protected $primaryKey = 'id_role';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_role',
        'deskripsi_role',
        'tipe_role',
        'path',
        'is_mobile',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function modul()
    {
        return $this->hasMany(Modul::class, 'id_role')->where('akses', 1);
    }
}
