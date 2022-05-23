<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Test extends Model
{
    use SoftDeletes;

    protected $table = 'test';

    protected $primaryKey = 'id_test';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'id_paket_soal',
        'waktu_mulai_pengerjaan',
        'waktu_selesai_pengerjaan',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];  //

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    // public function jawaban_test()
    // {
    //     return $this->hasMany(JawabanTest::class, 'id_jawaban_test');
    // }
}
