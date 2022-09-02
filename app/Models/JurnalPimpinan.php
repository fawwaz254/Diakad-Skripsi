<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalPimpinan extends Model
{
    use SoftDeletes;

    protected $table = 'jurnal_pimpinan';

    protected $primaryKey = 'id_jurnal_pimpinan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];
}
