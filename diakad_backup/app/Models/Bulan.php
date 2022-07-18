<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class Bulan extends Model
{
    use SoftDeletes;

    protected $table = 'bulan';

    protected $primaryKey = 'id_bulan';

	public $timestamps = true;

    protected $fillable = [
        'kode_bulan',
        'nm_bulan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}