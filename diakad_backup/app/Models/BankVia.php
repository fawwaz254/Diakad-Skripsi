<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BankVia
 */
class BankVia extends Model
{
    use SoftDeletes;

    protected $table = 'bank_via';

    protected $primaryKey = 'id_bank_via';

	public $timestamps = true;

    protected $fillable = [
        'kode_bank_via',
        'nm_bank_via',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}