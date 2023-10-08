<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailBiayaInternal
 */
class DetailBiayaInternal extends Model
{
    use SoftDeletes;

    protected $table = 'detail_biaya_internal';

    protected $primaryKey = 'id_detail_biaya_internal';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_biaya_internal',
        'nm_detail_biaya_internal',
        'besar_biaya',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kelompok_biaya_internal()
    {
        return $this->belongsTo(KelompokBiayaInternal::class, 'id_kelompok_biaya_internal');
    }

    public function subkategori_rapb()
    {
        return $this->belongsTo(SubkategoriRapb::class, 'id_subkategori_rapb');
    }
}
