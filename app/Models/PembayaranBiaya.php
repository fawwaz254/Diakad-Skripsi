<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PembayaranBiaya
 */
class PembayaranBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'pembayaran_biaya';

    protected $primaryKey = 'id_pembayaran_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_tagihan_biaya',
        'id_staff_bayar',
        'id_semester_bayar',
        'besar_pembayaran',
        'tgl_pembayaran',
        'id_bank',
        'id_bank_via',
        'nomor_transaksi',
        'keterangan',
        'is_tarik',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}