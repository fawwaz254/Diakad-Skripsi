<?php

use App\Models\Pengguna;
use App\Models\Sekolah;
use App\Models\Staff;
use App\Models\UnitKerja;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AddDataStafInAdminYayasan2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $yayasan = Pengguna::where('username', 'yayasan')->first();
        if ($yayasan) {
            $staf = Staff::where('id_pengguna', $yayasan->id_pengguna)->first();
            if ($staf) { } else {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $sekolah = Sekolah::first();
                $unit_kerja = UnitKerja::where('nm_unit_kerja', 'Tata Usaha')->first();
                $id = $sekolah->prefix . strtotime($now) . uniqid();
                $staff = new Staff;
                $staff->id_staff                 = $id;
                $staff->id_pengguna              = $yayasan->id_pengguna;
                /*$staff->id_jabatan_pegawai       = $input->id_jabatan_pegawai;*/
                $staff->id_unit_kerja            = $unit_kerja->id_unit_kerja;
                $staff->jenis_jabatan            = null;
                $staff->nik_ptk                  = null;
                $staff->jenis_kelamin            = '1';
                $staff->id_kota_lahir            = null;
                $staff->tgl_lahir                = null;
                $staff->nm_ibu_kandung           = null;
                $staff->alamat_jalan             = null;
                $staff->alamat_rt                = null;
                $staff->alamat_rw                = null;
                $staff->alamat_dusun             = null;
                $staff->alamat_kelurahan         = null;
                $staff->alamat_kecamatan         = null;
                $staff->alamat_kodepos           = null;
                $staff->alamat_kota              = null;
                $staff->alamat_provinsi          = null;
                $staff->alamat_latitude          = null;
                $staff->alamat_longitude         = null;
                $staff->id_agama                 = null;
                $staff->npwp_ptk                 = null;
                $staff->nm_wajib_pajak_ptk       = null;
                $staff->kewarganegaraan          = null;
                $staff->status_kawin             = null;
                $staff->nm_pasangan_ptk          = null;
                $staff->nip_pasangan_ptk         = null;
                $staff->id_jenis_pekerjaan_pasangan_ptk  = null;
                // $staff->nip_ptk                  = null;

                //section kepegawaian
                $staff->id_jenis_kepegawaian     = null;
                $staff->nip_staff                 = null;
                $staff->niy_nigk_ptk             = null;
                $staff->nuptk                    = null;
                $staff->id_jenis_ptk             = null;
                $staff->nomor_sk_pengangkatan    = null;
                $staff->tgl_sk_pengangkatan       = null;
                $staff->id_jenis_lembaga_pengangkat  = null;
                $staff->nomor_sk_cpns            = null;
                $staff->tgl_mulai_pns            = null;
                $staff->golongan_ptk             = null;
                $staff->id_jenis_sumber_gaji     = null;
                $staff->nomor_kartu_pegawai      = null;
                $staff->nomor_kartu_pasangan     = null;

                //section kompetensi khusus
                $staff->is_lisensi_kepsek        = null;
                $staff->id_jenis_keahlian_lab    = null;
                $staff->is_keahlian_braile       = null;
                $staff->is_keahlian_bahasa_isyarat   = null;

                //section kontak
                $staff->nomor_telp               = null;
                $staff->nomor_hp                 = null;
                $staff->email                    = null;


                //section penugasan
                $staff->is_sekolah_induk         = null;
                $staff->tgl_sk_penugasan         = null;
                $staff->nomor_sk_penugasan       = null;
                $staff->created_by               = 'migration';
                $staff->created_at               = $now;
                $staff->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
