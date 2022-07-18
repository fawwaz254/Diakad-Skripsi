<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PembayaranTunggakan
 */
class PembayaranTunggakan extends Model
{
    protected $table = 'pembayaran_tunggakan';

    protected $primaryKey = 'id_pembayaran_tunggakan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_semester_mulai',
        'id_semester_selesai',
        'besar_pembayaran',
        'tgl_pembayaran',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected $guarded = [];

    public function scopeIsInputByPengguna($query, $id_pengguna){
        return $query->where('pembayaran_tunggakan.created_by', $id_pengguna);
    }
}