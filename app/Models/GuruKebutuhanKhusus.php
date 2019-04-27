<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GuruKebutuhanKhusus
 */
class GuruKebutuhanKhusus extends Model
{
    use SoftDeletes;

    protected $table = 'guru_kebutuhan_khusus';

    protected $primaryKey = 'id_guru_kebutuhan_khusus';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_guru',
        'id_kebutuhan_khusus',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}