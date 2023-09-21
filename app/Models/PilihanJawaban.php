<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PilihanJawaban extends Model
{
    use SoftDeletes;

    protected $table = 'pilihan_jawaban';

    protected $primaryKey = 'id_pilihan_jawaban';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_soal',
        'nomer',
        'text',
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
