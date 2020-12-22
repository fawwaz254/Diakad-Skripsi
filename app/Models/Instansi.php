<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use SoftDeletes;

    protected $table        = 'instansi';
    protected $primaryKey   = 'id_instansi';
    public $incrementing    = false;
    public $timestamps      = true;

    protected $fillable = [
        'nm_instansi',
        'bidang_usaha',
        'alamat',
        'kontak',
        'website',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
