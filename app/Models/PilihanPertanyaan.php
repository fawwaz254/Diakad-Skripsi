<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PilihanPertanyaan extends Model
{
    use SoftDeletes;

    protected $table = 'pilihan_pertanyaan';

    protected $primaryKey = 'id_pilihan_pertanyaan';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_soal',
        'nomer',
        'text',
        'jawaban',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function soal()
    {
        return $this->belongsTo('App\Models\Soal', 'id_soal');
    }
}
