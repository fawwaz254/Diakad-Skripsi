<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaLogin extends Model
{
    protected $table = 'pengguna_login';

    protected $primaryKey = 'id_pengguna_login';

    protected $keyType = 'string';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
