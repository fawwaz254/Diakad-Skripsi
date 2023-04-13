<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketSoalKelas extends Model
{
    use SoftDeletes;

    protected $table = 'paket_soal_kelas';

    protected $primaryKey = 'id_paket_soal_kelas';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_paket_soal',
        'id_kelas',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
