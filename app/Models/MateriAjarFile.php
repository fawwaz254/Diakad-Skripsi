<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class MateriAjarFile extends Model
{
    use SoftDeletes;

    protected $table = 'materi_ajar_file';

    protected $primaryKey = 'id_materi_ajar_file';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function materi_ajar()
    {
        return $this->belongsTo(MateriAjar::class, 'id_materi_ajar','id_materi_ajar');
    }

}