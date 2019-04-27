<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkTugasTambahan
 */
class PtkTugasTambahan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_tugas_tambahan';

    protected $primaryKey = 'id_ptk_tugas_tambahan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'nm_jabatan_tambahan',
        'nomor_sk_tambahan',
        'tgl_mulai_tambahan',
        'tgl_selesai_tambahan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}