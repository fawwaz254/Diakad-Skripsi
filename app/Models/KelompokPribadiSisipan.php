<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KelompokPribadiSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'kelompok_pribadi_sisipan';

    protected $primaryKey = 'id_kelompok_pribadi_sisipan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_kelompok_pribadi_sisipan',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
