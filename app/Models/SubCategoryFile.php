<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisGedung
 */
class SubCategoryFile extends Model
{
    use SoftDeletes;

    protected $table = 'sub_category_file';

    protected $primaryKey = 'sub_category_file_id';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'category_file_name',
        'category_file_explanation',
        'category_file_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}