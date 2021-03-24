<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TagihanBiaya
 */
class TagihanBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'tagihan_biaya';

    protected $primaryKey = 'id_tagihan_biaya';

    protected $keyType = 'string';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_detail_biaya',
        'besar_biaya',
        'denda_biaya',
        'is_tagih',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function detail_biaya()
    {
        return $this->belongsTo('App\Models\DetailBiaya', 'id_detail_biaya');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }
}
