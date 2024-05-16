<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitasPengguna extends Model
{
    protected $table = 'log_aktivitas_pengguna';

    protected $primaryKey = 'id_log_aktivitas_pengguna';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
