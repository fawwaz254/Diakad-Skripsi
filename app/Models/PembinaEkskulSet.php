<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PembinaEkskulSet
 */
class PembinaEkskulSet extends Model
{
    use SoftDeletes;

    protected $table = 'pembina_ekskul_set';

    protected $primaryKey = 'id_pembina_ekskul_set';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_guru',
        'id_ekskul',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}