<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MapelRPPDetail
 */
class MapelRPPDetail extends Model
{
    protected $table = 'mapel_rpp_detail';

    protected $primaryKey = 'id_mapel_rpp_detail';

    public $timestamps = true;

    public $incrementing = true;

    protected $guarded = [];
}
