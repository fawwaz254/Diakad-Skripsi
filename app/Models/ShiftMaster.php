<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftMaster extends Model
{
    use SoftDeletes;

    protected $table = 'shift_masters';

    protected $primaryKey = 'id_shift_master';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_shift_master',
        'type',
        'code',
        'is_aktif_semester',
        'start_time',
        'end_time',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
