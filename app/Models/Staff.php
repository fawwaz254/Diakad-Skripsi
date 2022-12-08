<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Staff
 */
class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staff';

    protected $primaryKey = 'id_staff';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_pengguna',
        'id_unit_kerja',
        'nip_staff',
        'nik_ptk',
        'jenis_kelamin',
        'id_kota_lahir',
        'tgl_lahir',
        'nm_ibu_kandung',
        'alamat_jalan',
        'alamat_rt',
        'alamat_rw',
        'alamat_dusun',
        'alamat_kelurahan',
        'alamat_kecamatan',
        'alamat_kodepos',
        'alamat_kota',
        'alamat_provinsi',
        'alamat_latitude',
        'alamat_longitude',
        'id_agama',
        'npwp_ptk',
        'nm_wajib_pajak_ptk',
        'kewarganegaraan',
        'status_kawin',
        'nm_pasangan_ptk',
        'nip_pasangan_ptk',
        'id_jenis_pekerjaan_pasangan_ptk',
        'id_jenis_kepegawaian',
        'nip_ptk',
        'niy_nigk_ptk',
        'nuptk',
        'id_jenis_ptk',
        'nomor_sk_pengangkatan',
        'tgl_sk_pengangkatan',
        'id_jenis_lembaga_pengangkat',
        'nomor_sk_cpns',
        'tgl_mulai_pns',
        'golongan_ptk',
        'id_jenis_sumber_gaji',
        'nomor_kartu_pegawai',
        'nomor_kartu_pasangan',
        'is_lisensi_kepsek',
        'id_jenis_keahlian_lab',
        'is_keahlian_braile',
        'is_keahlian_bahasa_isyarat',
        'nomor_telp',
        'nomor_hp',
        'email',
        'nomor_sk_penugasan',
        'tgl_sk_penugasan',
        'tgl_mulai_penugasan',
        'is_sekolah_induk',
        'alasan_keluar',
        'tgl_keluar',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function unit_kerja()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
