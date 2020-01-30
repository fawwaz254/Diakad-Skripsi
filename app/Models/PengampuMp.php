<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengampuMp
 */
class PengampuMp extends Model
{
    use SoftDeletes;

    protected $table = 'pengampu_mp';

    protected $primaryKey = 'id_pengampu_mp';

    public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas_mp',
        'id_guru',
        'pjmp_pengampu_mp',
        'pjmp_uts',
        'pjmp_uas',
        'nomor_sk_mengajar',
        'tgl_sk_mengajar',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo('App\Models\Guru', 'id_guru');
    }
}
