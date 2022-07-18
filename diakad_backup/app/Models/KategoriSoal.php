<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class KategoriSoal extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_soal';

    protected $primaryKey = 'id_kategori_soal';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_kategori_soal',
        'id_pengguna',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    
    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
