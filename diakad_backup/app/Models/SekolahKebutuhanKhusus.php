<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SekolahKebutuhanKhusus
 */
class SekolahKebutuhanKhusus extends Model
{
    use SoftDeletes;

    protected $table = 'sekolah_kebutuhan_khusus';

    protected $primaryKey = 'id_sekolah_kebutuhan_khusus';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_sekolah',
        'id_kebutuhan_khusus',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}