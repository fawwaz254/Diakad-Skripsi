<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PribadiSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'pribadi_sisipan';

    protected $primaryKey = 'id_pribadi_sisipan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_pribadi_sisipan',
        'nm_pribadi_sisipan',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
