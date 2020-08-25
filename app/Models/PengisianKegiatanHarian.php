<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengisianKegiatanHarian
 */
class PengisianKegiatanHarian extends Model
{
    use SoftDeletes;

    protected $table = 'pengisian_kegiatan_harian';

    protected $primaryKey = 'id_pengisian_kegiatan_harian';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function pengguna_pengisi()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_pengisi', 'id_pengguna');
    }

    public function status_to_text()
    {
        switch($this->status_pengisian){
            case 0: 
                return 'Belum selesai';
            case 1: 
                return 'Normal';
            case 2: 
                return 'Warning';
            default: 
                return '';
        }
    }
}