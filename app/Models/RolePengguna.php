<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RolePengguna
 */
class RolePengguna extends Model
{
    use SoftDeletes;

    protected $table = 'role_pengguna';

    protected $primaryKey = 'id_role_pengguna';

	public $timestamps = true;

    public $incrementing = true;
    
    protected $fillable = [
        'id_role',
        'id_pengguna',
        'keterangan_role_pengguna',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function role(){
        return $this->belongsTo('App\Models\Role', 'id_role');
    }    

}