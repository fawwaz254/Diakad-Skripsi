<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaBaru
 */
class CalonSiswaBaru extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_baru';

    protected $primaryKey = 'id_c_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_c_siswa',
        'id_penerimaan',
        'kode_voucher',
        'password',
        'nm_c_siswa',
        'nik_siswa',
        'jenis_kelamin',
        'nisn_siswa',
        'id_agama',
        'id_kota_lahir',
        'tgl_lahir',
        'id_kota_ksk',
        'nomor_ksk',
        'nomor_identitas',
        'nomor_akta_lahir',
        'kewarganegaraan',
        'nm_kewarganegaraan',
        'id_kebutuhan_khusus',
        'alamat_jalan',
        'alamat_dusun',
        'alamat_kelurahan',
        'alamat_rt',
        'alamat_rw',
        'alamat_kecamatan',
        'alamat_kodepos',
        'alamat_kota',
        'alamat_provinsi',
        'alamat_latitude',
        'alamat_longitude',
        'nomor_hp',
        'id_jenis_tinggal',
        'anak_ke',
        'dari_x_bersaudara',
        'jarak_rumah_sekolah',
        'waktu_tempuh_sekolah_jam',
        'waktu_tempuh_sekolah_menit',
        'id_jenis_transportasi',
        'nomor_kks',
        'is_penerima_kps',
        'nomor_kps',
        'is_punya_kip',
        'nomor_kip',
        'nm_tertera_kip',
        'is_layak_pip',
        'id_jenis_layak_pip',
        'asal_sekolah',
        'nomor_ujian_sebelumnya',
        'nomor_ijasah_sebelumnya',
        'nomor_skhus_sebelumnya',
        'id_pilihan_jurusan_1',
        'id_pilihan_jurusan_2',
        'id_pilihan_jurusan_3',
        'nomor_ujian',
        'nis_siswa',
        'id_jurusan',
        'tgl_registrasi',
        'tgl_submit_form',
        'tgl_verifikasi_dokumen',
        'tgl_penetapan',
        'bayar_daftar_ulang',
        'tgl_diterima',
        'tgl_generate_nis',
        'tgl_cetak_kartu_pelajar',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}