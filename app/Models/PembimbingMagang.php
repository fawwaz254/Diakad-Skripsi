<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PembimbingMagang extends Model
{
    use SoftDeletes;

    protected $table = 'pembimbing_magang';

    protected $primaryKey = 'id_pembimbing_magang';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_periode_magang',
        'id_rekanan_magang',
        'id_pengguna',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeMagang::class, 'id_periode_magang');
    }

    public function rekanan()
    {
        return $this->belongsTo(RekananMagang::class, 'id_rekanan_magang');
    }

    public function presensiMagangSiswa()
    {
        return $this->hasMany(PresensiMagangSiswa::class, 'id_pembimbing_magang');
    }
}
