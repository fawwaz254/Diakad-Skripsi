<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailBiaya
 */
class DetailBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'detail_biaya';

    protected $primaryKey = 'id_detail_biaya';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_biaya_sekolah',
        'id_biaya',
        'id_kelompok_biaya_internal',
        'validasi_biaya',
        'besar_biaya',
        'keterangan_biaya',
        'id_jenis_detail_biaya',
        'id_bulan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function biaya_sekolah()
    {
        return $this->belongsTo(BiayaSekolah::class, 'id_biaya_sekolah');
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class, 'id_biaya');
    }

    public function bulan()
    {
        return $this->belongsTo(Bulan::class, 'id_bulan');
    }

    public function kelompok_biaya_internal()
    {
        return $this->belongsTo(KelompokBiayaInternal::class, 'id_kelompok_biaya_internal');
    }

    public function detail_internal()
    {
        return $this->hasManyThrough(
            DetailBiayaInternal::class,
            KelompokBiayaInternal::class,
            'id_kelompok_biaya_internal', // Foreign key on users table...
            'id_kelompok_biaya_internal', // Foreign key on posts table...
            'id_kelompok_biaya_internal', // Local key on countries table...
            'id_kelompok_biaya_internal' // Local key on users table...
        );
    }

    public function scopeIsValid($query){
        return $query->where('validasi_biaya', 1);
    }
}
