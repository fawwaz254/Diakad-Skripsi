<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KebutuhanKhusus
 */
class KebutuhanKhusus extends Model
{
    use SoftDeletes;

    protected $table = 'kebutuhan_khusus';

    protected $primaryKey = 'id_kebutuhan_khusus';

	public $timestamps = true;

    protected $fillable = [
        'kode_kebutuhan_khusus',
        'nm_kebutuhan_khusus',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}