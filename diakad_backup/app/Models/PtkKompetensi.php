<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkKompetensi
 */
class PtkKompetensi extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_kompetensi';

    protected $primaryKey = 'id_ptk_kompetensi';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'bidang_studi_kompetensi',
        'urutan_kompetensi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}