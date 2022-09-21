<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KomponenNilaiRaporSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_nilai_rapor_sisipan';

    protected $primaryKey = 'id_komponen_nilai';

	public $timestamps = true;

    public $incrementing = false;
    

    protected $guarded = [];

}
