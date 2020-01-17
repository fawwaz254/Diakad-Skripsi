<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RealisasiPakPembayaran
 */
class RealisasiPakPembayaran extends Model
{
    use SoftDeletes;

    protected $table = 'realisasi_pak_pembayaran';

    protected $primaryKey = 'id_realisasi_pak_pembayaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_realisasi_pak',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}