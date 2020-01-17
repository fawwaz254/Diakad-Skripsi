<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RealisasiPembayaran
 */
class RealisasiPembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'realisasi_pembayaran';

    protected $primaryKey = 'id_realisasi_pembayaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_realisasi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}