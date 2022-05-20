<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use SoftDeletes;

    protected $table = 'soal';

    protected $primaryKey = 'id_soal';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'id_pilihan_soal_benar',
        'content',
        'text',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function pengguna(){
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna');
    }

    public function pilihan_soal(){
        return $this->hasMany('App\Models\PilihanSoal', 'id_soal');
    }
}
