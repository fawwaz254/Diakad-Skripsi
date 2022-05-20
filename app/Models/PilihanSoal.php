<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PilihanSoal extends Model
{
    use SoftDeletes;

    protected $table = 'pilihan_soal';

    protected $primaryKey = 'id_pilihan_soal';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_soal',
        'number_option',
        'content',
        'text',
        'correct',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function soal(){
        return $this->belongsTo('App\Models\Soal', 'id_soal');
    }
}
