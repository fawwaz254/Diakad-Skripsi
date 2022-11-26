<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DokumenTandaTanganDigital 
 */
class DokumenTandaTanganDigital extends Model
{
    use SoftDeletes;

    protected $table = 'dokumen_tanda_tangan_digital';

    protected $primaryKey = 'id_tanda_tangan_digital';

    public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];
}
