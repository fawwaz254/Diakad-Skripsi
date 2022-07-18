<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaOrtu
 */
class CalonSiswaOrtu extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_ortu';

    protected $primaryKey = 'id_c_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_c_siswa',
        'nm_ayah',
        'nik_ayah',
        'tgl_lahir_ayah',
        'id_jenis_pendidikan_ayah',
        'id_jenis_pekerjaan_ayah',
        'id_jenis_penghasilan_ayah',
        'id_kebutuhan_khusus_ayah',
        'nm_ibu',
        'nik_ibu',
        'tgl_lahir_ibu',
        'id_jenis_pendidikan_ibu',
        'id_jenis_pekerjaan_ibu',
        'id_jenis_penghasilan_ibu',
        'id_kebutuhan_khusus_ibu',
        'nm_wali',
        'nik_wali',
        'tgl_lahir_wali',
        'id_jenis_pendidikan_wali',
        'id_jenis_pekerjaan_wali',
        'id_jenis_penghasilan_wali',
        'id_kebutuhan_khusus_wali',
        'alamat_jalan_ortu',
        'alamat_dusun_ortu',
        'alamat_kelurahan_ortu',
        'almat_rt_ortu',
        'alamat_rw_ortu',
        'alamat_kecamatan_ortu',
        'alamat_kodepos_ortu',
        'alamat_kota_ortu',
        'alamat_provinsi_ortu',
        'nomor_telp_ortu',
        'nomor_hp_ortu',
        'email_ortu',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}