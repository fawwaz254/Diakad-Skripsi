<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PenerimaanPetugas
 */
class PenerimaanPetugas extends Model
{
    use SoftDeletes;

    protected $table = 'penerimaan_petugas';

    protected $primaryKey = 'id_penerimaan_petugas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_penerimaan',
        'id_pengguna_petugas',
        'jabatan_petugas',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}