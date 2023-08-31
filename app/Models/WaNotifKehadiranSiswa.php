<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaNotifKehadiranSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'wa_notif_kehadiran_siswa';

    protected $primaryKey = 'id_notif';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_siswa',
    ];

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }
}
