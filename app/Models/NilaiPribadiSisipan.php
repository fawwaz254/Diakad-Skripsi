<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NilaiPribadiSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_pribadi_sisipan';

    protected $primaryKey = 'id_nilai_pribadi_sisipan';

    public $timestamps = true;

    public $incrementing = false;
    protected $fillable = [
        'id_pribadi_sisipan',
        'id_siswa',
        'id_semester',
        'nilai',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $guarded = [];
}
