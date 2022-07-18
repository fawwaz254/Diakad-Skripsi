<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KetSubkategoriRapb
 */
class KetSubkategoriRapb extends Model
{
    use SoftDeletes;

    protected $table = 'ket_subkategori_rapb';

    protected $primaryKey = 'id_ket_subkategori_rapb';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_subkategori_rapb',
        'kode_ket_subkategori_rapb',
        'nm_ket_subkategori_rapb',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}