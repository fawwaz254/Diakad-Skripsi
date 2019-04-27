<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RaporSubkategori
 */
class RaporSubkategori extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_subkategori';

    protected $primaryKey = 'id_rapor_subkategori';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_rapor_kategori',
        'nm_rapor_subkategori',
        'urutan_rapor_subkategori',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}