<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisKerjaSama extends Model
{
    use SoftDeletes;

    protected $tabel        = 'jenis_kerjasama';
    protected $primaryKey   = 'id_jenis_kerjasama';
    public $incrementing    = false;
    public $timestamps      = true;

    protected $fillable = [
        'nm_jenis_kerjasama',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
