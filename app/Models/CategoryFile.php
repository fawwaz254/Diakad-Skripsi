<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisGedung
 */
class CategoryFile extends Model
{
    use SoftDeletes;

    protected $table = 'category_file';

    protected $primaryKey = 'category_file_id';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'category_file_name',
        'category_file_explanation',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}