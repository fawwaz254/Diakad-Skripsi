<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriFileGuru extends Model
{
    use SoftDeletes;

    protected $table = 'category_file_guru';

    protected $primaryKey = 'category_file_guru_id';

    public $incrementing = false;

    public $timestamps = true;

    protected $guarded = [];
    public function pengguna()
    {
        return $this->belongsTo(pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
