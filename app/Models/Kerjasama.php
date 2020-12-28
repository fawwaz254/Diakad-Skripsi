<?php

namespace App\Models;

use App\Models\Instansi;
use App\Models\JenisKerjasama;
use App\Models\BerkasKerjasama;
use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kerjasama extends Model
{
    use SoftDeletes;
    use BaseModelTraits;

    protected $table        = 'kerjasama';
    protected $primaryKey   = 'id_kerjasama';
    public $incrementing    = false;
    public $timestamps      = true;

    protected $fillable = [
        'id_instansi',
        'id_jenis_kerjasama',
        'nm_kerjasama',
        'tanggal_kerjasama',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi');
    }

    public function jenisKerjasama()
    {
        return $this->belongsTo(JenisKerjasama::class, 'id_jenis_kerjasama');
    }

    public function berkasKerjasama()
    {
        return $this->hasMany(BerkasKerjasama::class, 'id_kerjasama')->orderBy('versi')->orderBy('created_at');
    }
}
