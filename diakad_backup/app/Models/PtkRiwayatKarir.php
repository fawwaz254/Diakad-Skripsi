<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkRiwayatKarir
 */
class PtkRiwayatKarir extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_riwayat_karir';

    protected $primaryKey = 'id_ptk_riwayat_karir';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'id_bentuk_pendidikan',
        'id_jenis_lembaga_pengangkat',
        'id_jenis_kepegawaian',
        'id_jenis_ptk',
        'nm_jenis_lembaga_pengangkat',
        'nomor_sk_kerja',
        'tgl_sk_kerja',
        'tgl_mulai_kerja',
        'tgl_selesai_kerja',
        'id_kota_kerja',
        'ttd_sk_kerja',
        'mapel_diajarkan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}