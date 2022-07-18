<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BiayaSekolah
 */
class BiayaSekolah extends Model
{
    use SoftDeletes;

    protected $table = 'biaya_sekolah';

    protected $primaryKey = 'id_biaya_sekolah';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelompok_biaya',
        'id_semester',
        'id_jalur',
        'besar_biaya_sekolah',
        'validasi_biaya_sekolah',
        'keterangan_biaya_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function detail_biaya()
    {
        return $this->hasMany(DetailBiaya::class, 'id_biaya_sekolah');
    }

    public function kelompok()
    {
        return $this->belongsTo(KelompokBiaya::class, 'id_kelompok_biaya');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

    public function jalur()
    {
        return $this->belongsTo(Jalur::class, 'id_jalur');
    }



}