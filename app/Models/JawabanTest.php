<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JawabanTest extends Model
{
    use SoftDeletes;

    protected $table = 'jawaban_test';

    protected $primaryKey = 'id_jawaban_test';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_test',
        'id_pengguna',
        'nomer',
        'id_soal',
        'id_pilihan_soal',
        'correct',
        'nilai',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];  //

    public function test()
    {
        return $this->belongsTo('App\Models\Test', 'id_test');
    }
    public function soal()
    {
        return $this->belongsTo('App\Models\Soal', 'id_soal');
    }
    public function pilihan_soal()
    {
        return $this->belongsTo(PilihanSoal::class, 'id_pilihan_soal');
    }
}
