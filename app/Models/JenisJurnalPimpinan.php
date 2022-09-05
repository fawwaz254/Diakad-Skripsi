<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisJurnalPimpinan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_jurnal_pimpinan';

    protected $primaryKey = 'id_jenis_jurpin';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];
}
