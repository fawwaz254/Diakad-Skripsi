<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Kota
 */
class Kota extends Model
{
    use SoftDeletes;

    protected $table = 'kota';

    protected $primaryKey = 'id_kota';

	public $timestamps = true;

    protected $fillable = [
        'id_provinsi',
        'nm_kota',
        'kode_kota',
        'tipe_dati2',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}