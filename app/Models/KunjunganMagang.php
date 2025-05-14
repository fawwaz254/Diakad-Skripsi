<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganMagang extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_magang';
    protected $primaryKey = 'id_kunjungan_magang';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_periode_magang',
        'id_rekanan_magang',
        'keterangan_kunjungan',
        'foto_kunjungan',
    ];

    public function periode_magang()
    {
        return $this->belongsTo(PeriodeMagang::class, 'id_periode_magang');
    }

    public function rekanan_magang()
    {
        return $this->belongsTo(RekananMagang::class, 'id_rekanan_magang');
    }
}
