<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Wisuda
 */
class Wisuda extends Model
{
    use SoftDeletes;

    protected $table = 'wisuda';

    protected $primaryKey = 'id_wisuda';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_wisuda',
        'keterangan_wisuda',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}