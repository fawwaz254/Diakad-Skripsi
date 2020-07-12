<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PembayaranTransaksi
 */
class PembayaranTransaksi extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran_transaksi';

    protected $primaryKey = 'id_pembayaran_transaksi';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_tagihan_biaya',
        'nomor_transaksi',
        'besar_pembayaran',
        'status_pembayaran',
        'token',
        'id_semester_bayar',
        'tgl_pembayaran',
        'keterangan',
    ];

    protected $guarded = [];

    public function tagihan_biaya()
    {
        return $this->belongsTo(TagihanBiaya::class, 'id_tagihan_biaya');
    }
}
