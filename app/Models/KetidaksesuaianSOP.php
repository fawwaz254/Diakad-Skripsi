<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KetidaksesuaianSOP extends Model
{
    use SoftDeletes;

    protected $table = 'ketidaksesuaian_sop';

    protected $primaryKey = 'id_ketidaksesuaian_sop';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_pengguna',
        'id_pengguna_input',
        'id_semester',
        'catatan_pelanggaran',
        'tgl_pelanggaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna');
    }

    public function pengguna_input()
    {
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna_input', 'id_pengguna',);
    }
}
