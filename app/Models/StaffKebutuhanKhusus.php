<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StaffKebutuhanKhusus
 */
class StaffKebutuhanKhusus extends Model
{
    use SoftDeletes;

    protected $table = 'staff_kebutuhan_khusus';

    protected $primaryKey = 'id_staff_kebutuhan_khusus';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_kebutuhan_khusus',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}