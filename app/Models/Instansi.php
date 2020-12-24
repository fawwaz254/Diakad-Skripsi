<?php

namespace App\Models;

use App\Models\Kerjasama;
use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use SoftDeletes;
    use BaseModelTraits;

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

    public function kejasama()
    {
        return $this->hasMany(Kerjasama::class, 'id_instansi');
    }
}
