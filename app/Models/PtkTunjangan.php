<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkTunjangan
 */
class PtkTunjangan extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_tunjangan';

    protected $primaryKey = 'id_ptk_tunjangan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_tunjangan',
        'nm_tunjangan',
        'instansi_tunjangan',
        'nomor_sk_tunjangan',
        'tgl_sk_tunjangan',
        'semester_tunjangan',
        'sumber_dana_tunjangan',
        'tahun_mulai_tunjangan',
        'tahun_selesai_tunjangan',
        'jumlah_dana_tunjangan',
        'is_masih_menerima',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}