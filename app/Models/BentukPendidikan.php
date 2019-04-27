<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BentukPendidikan
 */
class BentukPendidikan extends Model
{
    use SoftDeletes;

    protected $table = 'bentuk_pendidikan';

    protected $primaryKey = 'id_bentuk_pendidikan';

	public $timestamps = true;

    protected $fillable = [
        'kode_bentuk_pendidikan',
        'nm_bentuk_pendidikan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}