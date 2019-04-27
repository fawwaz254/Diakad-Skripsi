<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RoleDashboard
 */
class RoleDashboard extends Model
{
    use SoftDeletes;

    protected $table = 'role_dashboard';

    protected $primaryKey = 'id_role_dashboard';

	public $timestamps = true;

    public $incrementing = true;
    
    protected $fillable = [
        'id_role',
        'isi_dashboard',
        'is_aktif',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function role(){
        return $this->belongsTo('App\Models\Role', 'id_role');
    }    

}