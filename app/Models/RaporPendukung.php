<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaporPendukung extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_pendukung';

    protected $primaryKey = 'id_rapor_pendukung';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function komponen_rapor_pendukung()
    {
        return $this->hasMany(KomponenRaporPendukung::class, 'id_rapor_pendukung');
    }
}
