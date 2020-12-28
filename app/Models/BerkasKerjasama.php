<?php

namespace App\Models;

use App\Models\Kerjasama;
use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BerkasKerjasama extends Model
{
    use SoftDeletes;
    use BaseModelTraits;

    protected $table = 'berkas_kerjasama';
    protected $primaryKey = "id_berkas_kerjasama";
    public $incrementing    = false;
    public $timestamps      = true;

    protected $fillable = [
        'id_kerjasama',
        'nama_file',
        'type',
        'path',
        'versi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function kejasama()
    {
        return $this->hasMany(Kerjasama::class, 'id_instansi');
    }

}
