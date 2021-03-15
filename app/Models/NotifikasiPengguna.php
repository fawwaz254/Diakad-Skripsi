<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotifikasiPengguna
 */
class NotifikasiPengguna extends Model
{
    protected $table = 'notifikasi_pengguna';

    protected $primaryKey = 'id_notifikasi_pengguna';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'id_sekolah',
        'isi_notifikasi',
        'link_url',
        'status',
        'created_by'
    ];

    protected $guarded = [];

    public function getStatusAttribute($value){
        if($value == 1){
            return 'Belum dibaca';
        }else{
            return 'Sudah dibaca';
        }
    }

    public function getCreatedAtAttribute($value){
        return date_format(date_create($value), 'd M Y H:i');
    }

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}