<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EkskulWajib
 */
class EkskulWajib extends Model
{
    use SoftDeletes;

    protected $table = 'ekskul_wajib';

    protected $primaryKey = 'id_ekskul_wajib';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ekskul',
        'tingkat_kelas',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}