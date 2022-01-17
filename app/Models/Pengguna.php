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

    #public const PEGAWAI = 1;
    #public const GURU = 2;
    #public const SISWA = 3;
    #public const WALI_MURID = 4;
    #public const PELATIH_EKSKUL = 5;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
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

    public function getIsPegawaiAttribute(){
        return $this->status_join_table === self::PEGAWAI;
    }

    public function getIsGuruAttribute(){
        return $this->status_join_table === self::GURU;
    }

    public function getIsSiswaAttribute(){
        return $this->status_join_table === self::SISWA;
    }

    public function getIsWaliMuridAttribute(){
        return $this->status_join_table === self::WALI_MURID;
    }

    public function getIsPembinaEkskulAttribute(){
        return $this->status_join_table === self::PELATIH_EKSKUL;
    }

    public function fullname()
    {
        if( ! empty($this->gelar_depan) && ! empty($this->gelar_belakang)) {
            return $this->gelar_depan." ".$this->nm_pengguna.", ".$this->gelar_belakang;
        }
        elseif( ! empty($this->gelar_depan)) {
            return $this->gelar_depan." ".$this->nm_pengguna;   
        }
        elseif( ! empty($this->gelar_belakang)) {
            return $this->nm_pengguna.", ".$this->gelar_belakang;   
        }
        else {
            return $this->nm_pengguna; 
        }
    }

    public function role_pengguna()
    {
        return $this->hasMany('App\Models\RolePengguna', 'id_pengguna');
    }

    public function sekolah()
    {
        return $this->belongsTo('App\Models\Sekolah', 'id_sekolah');
    }

    public function status_pengguna()
    {
        return $this->belongsTo('App\Models\StatusPengguna', 'id_status_pengguna');
    }

    public function pengisian_kegiatan_harian()
    {
        return $this->hasMany(PengisianKegiatanHarian::class, 'id_pengguna');
    }

    public function status_join_to_text()
    {
        switch ($this->status_join_table) {
            case 1:
                return 'Tendik';
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

    public function path_join_to_text()
    {
        switch ($this->status_join_table) {
            case 1:
                return 'tendik';
            case 2:
                return 'guru';
            case 3:
                return 'siswa';
            case 4:
                return 'wali-murid';
            case 5:
                return 'pelatih-ekskul';
            default:
                return '';
        }
    }
}
