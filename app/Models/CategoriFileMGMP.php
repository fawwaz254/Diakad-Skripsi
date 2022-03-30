<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriFileMGMP extends Model
{
    use SoftDeletes;

    protected $table = 'category_file_mgmp';

    protected $primaryKey = 'category_file_mgmp_id';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];

    public function category_file_guru()
    {
        return $this->hasMany(CategoriFileGuru::class, 'category_file_mgmp_id', 'category_file_mgmp_id');
    }
}
