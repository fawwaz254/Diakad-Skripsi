<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InformasiPpdb
 */
class InformasiPpdb extends Model
{
    use SoftDeletes;

    protected $table = 'informasi_ppdb';

    protected $primaryKey = 'id_informasi_ppdb';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna_input',
        'isi_informasi',
        'is_aktif',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}