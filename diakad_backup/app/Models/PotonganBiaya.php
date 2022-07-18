<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PotonganBiaya
 */
class PotonganBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'potongan_biaya';

    protected $primaryKey = 'id_potongan_biaya';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function detail_potongan()
    {
        return $this->hasMany(DetailPotonganBiaya::class, 'id_potongan_biaya');
    }
}