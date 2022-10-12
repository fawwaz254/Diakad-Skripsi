<?php

namespace App\Http\Controllers\Guru\Biodata;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Guru as Guru;
use App\Models\Kota as Kota;
use App\Models\Agama as Agama;
use App\Models\Provinsi as Provinsi;
use App\Models\JenisPekerjaan as JenisPekerjaan;
use App\Models\JenisKepegawaian as JenisKepegawaian;
use App\Models\JenisPtk as JenisPtk;
use App\Models\JenisKeahlianLab as JenisKeahlianLab;
use App\Models\JenisSumberGaji as JenisSumberGaji;
use App\Models\JenisLembagaPengangkat as JenisLembagaPengangkat;
use App\Models\Pengguna;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class DataPribadiController extends BaseController
{
    public function viewDataPribadi(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_status_aktif_guru = LibDataSumberDaya::fetchDataStatusAktifGuru($auth_data);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $temp_guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $guru = LibGuru::fetchDataAllGuru($auth_data, $temp_guru->id_guru);

        $kota = Kota::where('kota.is_aktif', '=', 1)->orderBy('nm_kota', 'asc')->get();
        $provinsi = Provinsi::where('provinsi.is_aktif', '=', 1)->orderBy('nm_provinsi', 'asc')->get();
        $agama = Agama::get();
        $pegawai = JenisKepegawaian::get();
        $pekerjaan = JenisPekerjaan::get();
        $ptk = JenisPtk::get();
        $pengangkat = JenisLembagaPengangkat::get();
        $gaji = JenisSumberGaji::get();
        $lab = JenisKeahlianLab::get();

        return view('guru/biodata/data-pribadi/view-data-pribadi', compact('auth_data', 'data_status_aktif_guru', 'data_unit_kerja', 'guru', 'kota', 'provinsi', 'agama', 'pegawai', 'pekerjaan', 'ptk', 'pengangkat', 'gaji', 'lab'));
    }

    public function actionSaveDataPribadi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'nm_pengguna'           => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $id_pengguna = $guru->id_pengguna;

            // make object to find id
            $pengguna                           = Pengguna::find($id_pengguna);
            $pengguna->nm_pengguna              = $input->nm_pengguna;

            $pengguna->email_pengguna           = $input->email;
            $pengguna->nomor_hp_pengguna        = $input->nomor_hp;
            $pengguna->gelar_depan              = $input->gelar_depan;
            $pengguna->gelar_belakang           = $input->gelar_belakang;
            $pengguna->updated_by               = $input->auth_data->pengguna->id_pengguna;
            $pengguna->updated_at               = $now;
            $pengguna->save();
            
            /*$guru->id_jabatan_pegawai       = $input->id_jabatan_pegawai;*/
            $guru->updated_by               = $input->auth_data->pengguna->id_pengguna;
            $guru->updated_at               = $now;
            $guru->nik_ptk                  = $input->nik_ptk;
            $guru->jenis_kelamin            = $input->jenis_kelamin;
            $guru->id_kota_lahir            = $input->id_kota_lahir;
            $guru->tgl_lahir                = date_format(date_create($input->tgl_lahir), "Y-m-d");
            $guru->nm_ibu_kandung           = $input->nm_ibu_kandung;
            $guru->alamat_jalan             = $input->alamat_jalan;
            $guru->alamat_rt                = $input->alamat_rt;
            $guru->alamat_rw                = $input->alamat_rw;
            $guru->alamat_dusun             = $input->alamat_dusun;
            $guru->alamat_kelurahan         = $input->alamat_kelurahan;
            $guru->alamat_kecamatan         = $input->alamat_kecamatan;
            $guru->alamat_kodepos           = $input->alamat_kodepos;
            $guru->alamat_kota              = $input->alamat_kota;
            $guru->alamat_provinsi          = $input->alamat_provinsi;
            $guru->alamat_latitude          = $input->alamat_latitude;
            $guru->alamat_longitude         = $input->alamat_longitude;
            $guru->id_agama                 = $input->id_agama;
            $guru->npwp_ptk                 = $input->npwp_ptk;
            $guru->nm_wajib_pajak_ptk       = $input->nm_wajib_pajak_ptk;
            $guru->kewarganegaraan          = $input->kewarganegaraan;
            $guru->status_kawin             = $input->status_kawin;
            $guru->nm_pasangan_ptk          = $input->nm_pasangan_ptk;
            $guru->nip_pasangan_ptk         = $input->nip_pasangan_ptk;
            $guru->id_jenis_pekerjaan_pasangan_ptk  = $input->id_jenis_pekerjaan_pasangan_ptk;

            //section kepegawaian
            $guru->id_jenis_kepegawaian     = $input->id_jenis_kepegawaian;
            $guru->niy_nigk_ptk             = $input->niy_nigk_ptk;
            $guru->nuptk                    = $input->nuptk;
            $guru->id_jenis_ptk             = $input->id_jenis_ptk;
            $guru->nomor_sk_pengangkatan    = $input->nomor_sk_pengangkatan;
            $guru->tgl_sk_pengangkatan       = date_format(date_create($input->tgl_sk_pengangkatan), "Y-m-d");
            $guru->id_jenis_lembaga_pengangkat  = $input->id_jenis_lembaga_pengangkat;
            $guru->nomor_sk_cpns            = $input->nomor_sk_cpns;
            $guru->tgl_mulai_pns            = date_format(date_create($input->tgl_mulai_pns), "Y-m-d");
            $guru->golongan_ptk             = $input->golongan_ptk;
            $guru->id_jenis_sumber_gaji     = $input->id_jenis_sumber_gaji;
            $guru->nomor_kartu_pegawai      = $input->nomor_kartu_pegawai;
            $guru->nomor_kartu_pasangan     = $input->nomor_kartu_pasangan;

            //section kompetensi khusus
            $guru->is_lisensi_kepsek        = $input->is_lisensi_kepsek;
            $guru->id_jenis_keahlian_lab    = $input->id_jenis_keahlian_lab;
            $guru->is_keahlian_braile       = $input->is_keahlian_braile;
            $guru->is_keahlian_bahasa_isyarat   = $input->is_keahlian_bahasa_isyarat;

            //section kontak
            $guru->nomor_telp               = $input->nomor_telp;
            $guru->nomor_hp                 = $input->nomor_hp;
            $guru->email                    = $input->email;

            //section penugasan
            $guru->is_sekolah_induk         = $input->is_sekolah_induk;
            $guru->tgl_sk_penugasan         = date_format(date_create($input->tgl_sk_penugasan), "Y-m-d");
            $guru->nomor_sk_penugasan       = $input->nomor_sk_penugasan;
            $guru->save();


            return [
                'status' => 200, // SUCCESS AND LOAD CONTENT
                'message' => 'Update Data Pribadi Successfully'
            ];
        }
    }
}
