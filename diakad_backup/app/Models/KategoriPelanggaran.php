<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KategoriPelanggaran
 */
class KategoriPelanggaran extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_pelanggaran';

    protected $primaryKey = 'id_kategori_pelanggaran';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_kategori_pelanggaran',
        'tingkat_kategori_pelanggaran',
        'keterangan_kategori_pelanggaran',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function subkategori_pelanggaran()
    {
        return $this->hasMany('App\Models\SubkategoriPelanggaran', 'id_kategori_pelanggaran');
    }
}
