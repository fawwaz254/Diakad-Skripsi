<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailPotonganBiaya
 */
class DetailPotonganBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'detail_potongan_biaya';

    protected $primaryKey = 'id_detail_potongan_biaya';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

}