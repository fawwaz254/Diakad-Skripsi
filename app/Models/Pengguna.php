<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Pengguna
 */
class Pengguna extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_status_pengguna',
        'id_sekolah',
        'nm_pengguna',
        'username',
        'password',
        'must_change_password',
        'status_join_table',
        'gelar_depan',
        'gelar_belakang',
        'remember_token',
        'api_key',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function role_pengguna(){
        return $this->hasMany('App\Models\RolePengguna', 'id_pengguna');
    }

    public function sekolah(){
        return $this->belongsTo('App\Models\Sekolah', 'id_sekolah');
    }

    public function status_join_to_text(){
        switch($this->status_join_table){
            case 1:  
                return 'Pegawai'; 
            case 2: 
                return 'Guru'; 
            case 3: 
                return 'Siswa';
            case 4: 
                return 'Wali Murid';
            case 5: 
                return 'Pelatih Ekskul';
            default:
                return '';
        }
    }

}