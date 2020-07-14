<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PembayaranTrs
 */
class PembayaranTrs extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran_trs';

    protected $primaryKey = 'id_pembayaran_trs';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'nomor_transaksi',
        'besar_pembayaran',
        'status_pembayaran',
        'token',
        'id_semester_bayar',
        'tgl_pembayaran',
        'keterangan',
    ];

    protected $guarded = [];
 
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
