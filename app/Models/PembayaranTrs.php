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

    public function pembayaran_trs_detail()
    {
        return $this->hasMany(PembayaranTrsDetail::class, 'id_pembayaran_trs');
    }

    public function status_pembayaran_to_text()
    {
        if ($this->status_pembayaran == 0) {
            return 'Waiting for payment';
        } else if ($this->status_pembayaran == 1) {
            return 'Success';
        } else if ($this->status_pembayaran == 10) {
            return 'Expired';
        } else {
            return '';
        }
    }
}
