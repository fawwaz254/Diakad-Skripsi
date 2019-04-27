<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KomplainSarpras
 */
class KomplainSarpras extends Model
{
    use SoftDeletes;

    protected $table = 'komplain_sarpras';

    protected $primaryKey = 'id_komplain_sarpras';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ruangan',
        'id_inventaris_ruangan',
        'id_buku_alat',
        'id_siswa_komplain',
        'id_guru_komplain',
        'keterangan_komplain',
        'is_urgent',
        'is_sudah_perbaikan',
        'id_guru_sarpras',
        'tgl_perbaikan',
        'keterangan_perbaikan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}