<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VoucherTarif
 */
class VoucherTarif extends Model
{
    use SoftDeletes;

    protected $table = 'voucher_tarif';

    protected $primaryKey = 'id_voucher_tarif';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_semester',
        'id_jurusan',
        'tarif',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
