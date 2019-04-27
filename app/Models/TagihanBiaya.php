<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TagihanBiaya
 */
class TagihanBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'tagihan_biaya';

    protected $primaryKey = 'id_tagihan_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_detail_biaya',
        'besar_biaya',
        'denda_biaya',
        'is_tagih',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}