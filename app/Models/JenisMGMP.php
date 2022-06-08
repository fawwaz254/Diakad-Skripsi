<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisMGMP extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_MGMP';

    protected $primaryKey = 'id_jenis_MGMP';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

}
