<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Penerimaan
 */
class Penerimaan extends Model
{
    use SoftDeletes;

    protected $table = 'penerimaan';

    protected $primaryKey = 'id_penerimaan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_jalur',
        'id_semester',
        'gelombang_penerimaan',
        'tahun_penerimaan',
        'nm_penerimaan',
        'nm_semester_penerimaan',
        'jml_pilihan_jurusan',
        'tgl_awal_registrasi',
        'tgl_akhir_registrasi',
        'tgl_awal_verifikasi',
        'tgl_akhir_verifikasi',
        'tgl_penetapan',
        'tgl_pengumuman',
        'tgl_awal_voucher',
        'tgl_akhir_voucher',
        'is_pendaftaran_online',
        'is_verifikasi',
        'is_bayar_voucher',
        'nomor_rekening_transfer',
        'biaya_daftar_ulang',
        'jenis_penerimaan',
        'is_aktif',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}