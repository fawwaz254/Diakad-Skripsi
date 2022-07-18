<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 */
class Setting extends Model
{

    protected $table = 'setting';

    protected $primaryKey = 'id_setting';

	public $timestamps = false;

    protected $fillable = [
        'key_setting',
        'value',
        'keterangan'
    ];

    protected $guarded = [];






}