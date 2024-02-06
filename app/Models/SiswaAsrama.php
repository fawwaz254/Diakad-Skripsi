<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiswaAsrama extends Model
{
    use SoftDeletes;

    protected $table = 'siswa_asrama';

    protected $primaryKey = 'id_siswa_asrama';

    protected $keyType = 'string';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(ShiftMaster::class,  'id_siswa');
    }
}
