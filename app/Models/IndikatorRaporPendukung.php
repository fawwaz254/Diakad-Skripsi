<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndikatorRaporPendukung extends Model
{
    use SoftDeletes;

    protected $table = 'indikator_rapor_pendukung';

    protected $primaryKey = 'id_indikator_rapor_pendukung';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

    public function komponen_rapor_pendukung()
    {
        return $this->belongsTo(KomponenRaporPendukung::class, 'id_komponen_rapor_pendukung');
    }

    public function predikat_rapor_pendukung()
    {
        return $this->hasMany(PredikatRaporPendukung::class, 'id_indikator_rapor_pendukung');
    }
}
