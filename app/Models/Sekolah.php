<?php

namespace App\Models;

use App\FileSekolah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Sekolah
 */
class Sekolah extends Model
{
    use SoftDeletes;

    protected $table = 'sekolah';

    protected $primaryKey = 'id_sekolah';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_sekolah',
        'prefix',
        'nm_singkat_sekolah',
        'url_sekolah',
        'http_host',
        'url_ppdb_online',
        'url_ujian_online',
        'url_kantin_online',
        'url_perpus_online',
        'is_subscribe',
        'npsn_sekolah',
        'id_bentuk_pendidikan',
        'alamat_jalan',
        'alamat_kelurahan',
        'alamat_kecamatan',
        'alamat_kota',
        'alamat_provinsi',
        'alamat_rt',
        'alamat_rw',
        'alamat_dusun',
        'alamat_kodepos',
        'alamat_latitude',
        'alamat_longitude',
        'nomor_sk_pendirian_sekolah',
        'tgl_sk_pendirian_sekolah',
        'status_kepemilikan',
        'nm_yayasan_sekolah',
        'nomor_sk_izin_operasional',
        'tgl_sk_izin_operasional',
        'is_mbs',
        'luas_tanah_milik_sekolah',
        'luas_tanah_non_milik_sekolah',
        'nm_wajib_pajak_sekolah',
        'npwp_sekolah',
        'nomor_telp_sekolah',
        'nomor_fax_sekolah',
        'email_sekolah',
        'website_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'alamat_kota', 'id_kota');
    }

    public function fileSekolah()
    {
        return $this->hasMany(FileSekolah::class, 'id_sekolah');
    }
}
