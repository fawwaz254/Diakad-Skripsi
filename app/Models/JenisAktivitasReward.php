<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAktivitasReward extends Model
{
    use HasFactory;

    protected $table = 'jenis_aktivitas_reward';

    public $timestamps = true;

    public $incrementing = true;

    protected $guarded = [];

    public function aktivitasRewardSiswa() 
    {
        return $this->hasMany(AktivitasRewardSiswa::class, 'id_jenis_aktivitas_reward', 'id_jenis_aktivitas_reward'); 
    }

    public static function cekJenisAktivitas($nama) {
        $model = self::where('nm_jenis_aktivitas_reward', 'like', "%$nama%")->get()->first();
        if ($model) {
            return $model->id_jenis_aktivitas_reward;
        } else {
            return null;
        }
    }
}
