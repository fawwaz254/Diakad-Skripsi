<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RewardSiswa
 */
class RewardSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'reward_siswa';

    protected $primaryKey = 'id_reward_siswa';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'id_pengguna_reward_siswa',
        'nm_reward_siswa',
        'deskripsi_reward_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }

    public function pemberi_reward()
    {
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna_reward_siswa');
    }
}
