<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubCategoryFileMGMP extends Model
{
    use SoftDeletes;

    protected $table = 'sub_category_file_mgmp';

    protected $primaryKey = 'sub_category_file_id';

    public $incrementing = false;

	public $timestamps = true;

    protected $guarded = [];
}
