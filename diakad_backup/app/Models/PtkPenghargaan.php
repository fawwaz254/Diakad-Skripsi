<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkPenghargaan
 */
class PtkPenghargaan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_penghargaan';

    protected $primaryKey = 'id_ptk_penghargaan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'tingkat_penghargaan',
        'jenis_penghargaan',
        'nm_penghargaan',
        'tahun_penghargaan',
        'instansi_penghargaan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}