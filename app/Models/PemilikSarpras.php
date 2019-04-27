<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PemilikSarpras
 */
class PemilikSarpras extends Model
{
    use SoftDeletes;

    protected $table = 'pemilik_sarpras';

    protected $primaryKey = 'id_pemilik_sarpras';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'kode_pemilik_sarpras',
        'nm_pemilik_sarpras',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}