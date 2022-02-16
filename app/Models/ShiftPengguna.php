<?php

namespace App\Models;

use App\Models\ShiftMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ShiftPengguna extends Model
{
    use SoftDeletes;

    protected $table = 'shift_penggunas';

    protected $primaryKey = 'id_shift_pengguna';

    public $timestamps = true;

    public $incrementing = false;
    protected $fillable = [
        'id_shift_pengguna',
        'id_pengguna',
        'date',
        'id_shift_master',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function shift_master()
    {
        return $this->belongsTo(ShiftMaster::class);
    }
}
