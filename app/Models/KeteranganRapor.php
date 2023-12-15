<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KeteranganRapor extends Model
{
    use SoftDeletes;

    protected $table = 'keterangan_rapor';

    protected $primaryKey = 'id_keterangan_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_rapor',
        'id_komponen_jenis_rapor',
        'keterangan_a',
        'keterangan_b',
        'keterangan_c',
        'keterangan_d',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
