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
        'id_ketegori_soal',
        'id_pilihan_soal_benar',
        'content',
        'text',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kategori_soal(){
        return $this->belongsTo('App\Models\KategoriSoal', 'id_kategori_soal');
    }

    public function pilihan_soal(){
        return $this->hasMany('App\Models\PilihanSoal', 'id_soal');
    }
}
