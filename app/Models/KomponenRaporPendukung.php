<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KomponenRaporPendukung extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_rapor_pendukung';

    protected $primaryKey = 'id_komponen_rapor_pendukung';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function rapor_pendukung()
    {
        return $this->belongsTo(RaporPendukung::class, 'id_rapor_pendukung');
    }

    public function indikator_rapor_pendukung()
    {
        return $this->hasMany(IndikatorRaporPendukung::class, 'id_komponen_rapor_pendukung');
    }
}
