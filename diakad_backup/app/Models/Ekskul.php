<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ekskul
 */
class Ekskul extends Model
{
    use SoftDeletes;

    protected $table = 'ekskul';

    protected $primaryKey = 'id_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_ekskul',
        'nomor_sk_ekskul',
        'tgl_sk_ekskul',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}