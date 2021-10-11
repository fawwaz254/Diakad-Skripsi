<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class SubCategoryFile extends Model
{
    use SoftDeletes;

    protected $table = 'sub_category_file';

    protected $primaryKey = 'sub_category_file_id';

    public $incrementing = false;

	public $timestamps = true;

    protected $guarded = [];

}