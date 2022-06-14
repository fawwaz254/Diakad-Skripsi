<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FPAttendance
 */
class FPAttendance extends Model
{
    use SoftDeletes;

    protected $table = 'fp_attendances';

    protected $primaryKey = 'id_fp_attendance';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'username',
        'status',
        'tanggal',
        'fp_date',
    ];

    protected $guarded = [];






}