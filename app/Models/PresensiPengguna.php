<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PresensiHarian
 */
class PresensiPengguna extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_pengguna';

    protected $primaryKey = 'id_presensi_pengguna';

    public $timestamps = true;

    public $incrementing = false;


    protected $fillable = [
        'id_pengguna',
        'status_join_table',
        'unit',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function shiftPengguna()
    {
        return $this->belongsTo(ShiftPengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function pengguna()
    {
        return  $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
