<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPaketSoal extends Model
{
    use SoftDeletes;

    protected $table = 'detail_paket_soal';

    protected $primaryKey = 'id_detail_paket_soal';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_paket_soal',
        'id_soal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function soal()
    {
        return $this->belongsTo('App\Models\Soal', 'id_soal');
    }
    public function paket_soal()
    {
        return $this->belongsTo(PaketSoal::class, 'id_paket_soal');
    }
}
