<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MateriAjarView extends Model
{
    use SoftDeletes;

    protected $table = 'materi_ajar_view';

    protected $primaryKey = 'id_materi_ajar_view';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna','id_pengguna');
    }
}
