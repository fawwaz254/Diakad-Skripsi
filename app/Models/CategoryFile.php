<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class CategoryFile extends Model
{
    use SoftDeletes;

    protected $table = 'category_file';

    protected $primaryKey = 'category_file_id';

    public $incrementing = false;
    
	public $timestamps = true;

    protected $guarded = [];

}