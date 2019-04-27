<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Penetapan
 */
class Penetapan extends Model
{
    use SoftDeletes;

    protected $table = 'penetapan';

    protected $primaryKey = 'id_penetapan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_penetapan',
        'nomor_sk_penetapan',
        'tgl_penetapan',
        'periode',
        'is_aktif',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}