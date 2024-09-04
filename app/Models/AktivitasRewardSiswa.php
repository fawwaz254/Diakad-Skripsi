<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasRewardSiswa extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_reward_siswa';

    protected $primaryKey = 'id_aktivitas_reward_siswa';

    public $timestamps = true;

    public $incrementing = true;

    protected $guarded = [];

    public function jenisAktivitasReward() 
    {
        return $this->belongsTo(JenisAktivitasReward::class, 'id_jenis_aktivitas_reward', 'id_jenis_aktivitas_reward');
    }
}
