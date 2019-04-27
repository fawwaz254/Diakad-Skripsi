<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Jalur
 */
class Jalur extends Model
{
    use SoftDeletes;

    protected $table = 'jalur';

    protected $primaryKey = 'id_jalur';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_jalur',
        'kode_jalur',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}