<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StatusPengguna
 */
class StatusPengguna extends Model
{
    use SoftDeletes;

    protected $table = 'status_pengguna';

    protected $primaryKey = 'id_status_pengguna';

    protected $keyType = 'string';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'status_join_table',
        'nm_status_pengguna',
        'aktif_status_pengguna',
        'kode_status_pengguna',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $guarded = [];

}
