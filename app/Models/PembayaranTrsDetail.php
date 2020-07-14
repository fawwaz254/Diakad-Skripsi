<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PembayaranTrsDetail
 */
class PembayaranTrsDetail extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran_trs_detail';

    protected $primaryKey = 'id_pembayaran_trs_detail';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pembayaran_trs',
        'id_tagihan_biaya',
        'besar_pembayaran',
        'keterangan',
    ];

    protected $guarded = [];

    public function pembayaran_trs()
    {
        return $this->belongsTo(PembayaranTrs::class, 'id_pembayaran_trs');
    }

    public function tagihan_biaya()
    {
        return $this->belongsTo(TagihanBiaya::class, 'id_tagihan_biaya');
    }
}
