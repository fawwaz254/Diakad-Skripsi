<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UjianMp
 */
class UjianMp extends Model
{
    use SoftDeletes;

    protected $table = 'ujian_mp';

    protected $primaryKey = 'id_ujian_mp';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kegiatan',
        'id_kelas_mp',
        'nm_ujian_mp',
        'tgl_ujian_mp',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'is_online',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kelas_mp()
    {
        return $this->belongsTo('App\Models\KelasMp', 'id_kelas_mp');
    }




}