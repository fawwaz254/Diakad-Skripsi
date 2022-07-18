<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bank
 */
class Bank extends Model
{
    use SoftDeletes;

    protected $table = 'bank';

    protected $primaryKey = 'id_bank';

	public $timestamps = true;

    protected $fillable = [
        'kode_bank',
        'nm_bank',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}