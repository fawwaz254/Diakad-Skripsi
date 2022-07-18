<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Supplier
 */
class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'supplier';

    protected $primaryKey = 'id_supplier';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'nm_supplier',
        'cp_supplier_1',
        'cp_supplier_2',
        'alamat_supplier',
        'nomor_sk_kerjasama',
        'tgl_awal_kerjasama',
        'tgl_akhir_kerjasama',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}