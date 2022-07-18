<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerpusPinjaman
 */
class PerpusPinjaman extends Model
{
    use SoftDeletes;

    protected $table = 'perpus_pinjaman';

    protected $primaryKey = 'id_perpus_pinjaman';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_perpus_buku',
        'id_siswa',
        'id_staff_pinjaman',
        'tgl_pinjaman',
        'lama_pinjaman',
        'keterangan_pinjaman',
        'tgl_deadline_kembali',
        'id_staff_kembali',
        'tgl_kembali',
        'keterangan_kembali',
        'denda_kembali',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}