<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubkategoriPelanggaran
 */
class SubkategoriPelanggaran extends Model
{
    use SoftDeletes;

    protected $table = 'subkategori_pelanggaran';

    protected $primaryKey = 'id_subkategori_pelanggaran';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kategori_pelanggaran',
        'nm_subkategori_pelanggaran',
        'tingkat_subkategori_pelanggaran',
        'poin_subkategori_pelanggaran',
        'keterangan_subkategori_pelanggaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}