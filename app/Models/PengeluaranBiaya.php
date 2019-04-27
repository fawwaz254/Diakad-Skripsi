<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengeluaranBiaya
 */
class PengeluaranBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'pengeluaran_biaya';

    protected $primaryKey = 'id_pengeluaran_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengeluaran_biaya_subkategori',
        'id_semester',
        'id_staff',
        'tgl_pengeluaran_biaya',
        'besar_pengeluaran_biaya',
        'keterangan_pengeluaran_biaya',
        'is_upload_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}