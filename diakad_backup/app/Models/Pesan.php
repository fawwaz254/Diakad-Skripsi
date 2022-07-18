<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pesan
 */
class Pesan extends Model
{
    use SoftDeletes;

    protected $table = 'pesan';

    protected $primaryKey = 'id_pesan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna_kirim',
        'id_pengguna_terima',
        'isi_pesan',
        'nm_pesan_file',
        'is_dibaca',
        'is_hapus_pengirim',
        'is_hapus_penerima',
        'is_tarik_pesan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}