<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PerawatanSarpras
 */
class PerawatanSarpras extends Model
{
    use SoftDeletes;

    protected $table = 'perawatan_sarpras';

    protected $primaryKey = 'id_perawatan_sarpras';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ruangan',
        'id_inventaris_ruangan',
        'id_buku_alat',
        'tgl_perawatan',
        'keterangan_perawatan',
        'is_sudah_perawatan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}