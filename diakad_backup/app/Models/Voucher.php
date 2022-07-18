<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Voucher
 */
class Voucher extends Model
{
    use SoftDeletes;

    protected $table = 'voucher';

    protected $primaryKey = 'id_voucher';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_penerimaan',
        'id_voucher_tarif',
        'kode_voucher',
        'pin_password',
        'tgl_ambil',
        'is_aktif',
        'tgl_bayar',
        'besar_biaya',
        'nomor_transaksi',
        'id_bank',
        'id_bank_via',
        'is_tagih_bank',
        'keterangan_voucher',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}