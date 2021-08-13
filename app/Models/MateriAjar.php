<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class MateriAjar extends Model
{
    use SoftDeletes;

    protected $table = 'materi_ajar';

    protected $primaryKey = 'id_materi_ajar';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function materi_ajar_file()
    {
        return $this->hasMany(MateriAjarFile::class, 'id_materi_ajar','id_materi_ajar');
    }

}