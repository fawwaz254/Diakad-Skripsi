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

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];

    public function category_file_role()
    {
        return $this->hasMany(CategoryFileRole::class, 'category_file_id', 'category_file_id');
    }
}
