<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class FilePengguna extends Model
{
    use SoftDeletes;

    protected $table = 'file_pengguna';

    protected $primaryKey = 'file_pengguna_id';

    public $incrementing = false;
    
	public $timestamps = true;

    protected $guarded = [];

}