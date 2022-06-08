<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FPDevice
 */
class FPDevice extends Model
{
    use SoftDeletes;

    protected $table = 'fp_devices';

    protected $primaryKey = 'id_fp_device';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_fp_device',
        'sn',
        'ip_address',
        'port',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}