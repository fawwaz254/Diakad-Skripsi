<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubkategoriRapb
 */
class SubkategoriRapb extends Model
{
    use SoftDeletes;

    protected $table = 'subkategori_rapb';

    protected $primaryKey = 'id_subkategori_rapb';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kategori_rapb',
        'kode_subkategori_rapb',
        'nm_subkategori_rapb',
        'deskripsi_subkategori_rapb',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(KategoriRapb::class, 'id_kategori_rapb');
    }




}