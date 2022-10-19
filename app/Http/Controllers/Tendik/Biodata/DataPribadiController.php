<?php

namespace App\Http\Controllers\Tendik\Biodata;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\Staff as Staff;
use App\Models\Kota as Kota;
use App\Models\Agama as Agama;
use App\Models\Provinsi as Provinsi;
use App\Models\JenisPekerjaan as JenisPekerjaan;
use App\Models\JenisKepegawaian as JenisKepegawaian;
use App\Models\JenisPtk as JenisPtk;
use App\Models\JenisKeahlianLab as JenisKeahlianLab;
use App\Models\JenisSumberGaji as JenisSumberGaji;
use App\Models\JenisLembagaPengangkat as JenisLembagaPengangkat;
use App\Models\GuruPiket as GuruPiket;

use Illuminate\Support\Facades\Hash;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibTendik;

use Auth;
use DB;
use Session;
use Validator;

class DataPribadiController extends BaseController{

    public function viewDataPribadi(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $staff = Staff::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $data_status_aktif_tendik = LibDataSumberDaya::fetchDataStatusAktifTendik($auth_data);
        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $tendik = LibTendik::fetchDataAllTendik($auth_data, $staff->id_staff);

        $kota = Kota::where('kota.is_aktif','=',1)->get();
        $provinsi = Provinsi::where('provinsi.is_aktif','=',1)->get();
        $agama = Agama::get();
        $pegawai = JenisKepegawaian::get();
        $pekerjaan = JenisPekerjaan::get();
        $ptk = JenisPtk::get();
        $pengangkat = JenisLembagaPengangkat::get();
        $gaji = JenisSumberGaji::get();
        $lab = JenisKeahlianLab::get();

    	return view('tendik/biodata/data-pribadi/view-data-pribadi',compact('auth_data','tendik','data_status_aktif_tendik','data_unit_kerja','kota','provinsi','agama','pegawai','pekerjaan','ptk','pengangkat','gaji','lab'));
    }

    public function actionInputTendik(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_pengguna'           => 'required',
            'nip_staff'              => 'required',
            /*'id_jabatan_pegawai'    => 'required',*/
            'id_unit_kerja'         => 'required',
            // 'jenis_jabatan'         => 'required',
            'id_status_pengguna'    => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if($mode == 'add') {
                $pengguna                           = new Pengguna;
                $pengguna->id_pengguna              = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pengguna->id_status_pengguna       = $input->id_status_pengguna;
                $pengguna->id_sekolah               = $input->auth_data->pengguna->id_sekolah;
                $pengguna->nm_pengguna              = $input->nm_pengguna;
                $pengguna->username                 = $input->nip_staff;
                $pengguna->password                 = Hash::make($input->nip_staff);
                $pengguna->must_change_password     = 1;
                $pengguna->status_join_table        = 1;
                $pengguna->email_pengguna           = $input->email;
                $pengguna->nomor_hp_pengguna        = $input->nomor_hp;
                $pengguna->created_by               = $input->auth_data->pengguna->id_pengguna;
                $pengguna->created_at               = $now;
                $pengguna->gelar_depan              = $input->gelar_depan;
                $pengguna->gelar_belakang           = $input->gelar_belakang;
                $pengguna->save();
                
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $staff                           = new Staff;
                $staff->id_staff                 = $id;
                $staff->id_pengguna              = $pengguna->id_pengguna;
                /*$staff->id_jabatan_pegawai       = $input->id_jabatan_pegawai;*/
                $staff->id_unit_kerja            = $input->id_unit_kerja;
                $staff->jenis_jabatan            = $input->jenis_jabatan;
                $staff->nik_ptk                  = $input->nik_ptk;
                $staff->jenis_kelamin            = $input->jenis_kelamin;
                $staff->id_kota_lahir            = $input->id_kota_lahir;
                $staff->tgl_lahir                = date_format(date_create($input->tgl_lahir),"Y-m-d");
                $staff->nm_ibu_kandung           = $input->nm_ibu_kandung;
                $staff->alamat_jalan             = $input->alamat_jalan;
                $staff->alamat_rt                = $input->alamat_rt;
                $staff->alamat_rw                = $input->alamat_rw;
                $staff->alamat_dusun             = $input->alamat_dusun;
                $staff->alamat_kelurahan         = $input->alamat_kelurahan;
                $staff->alamat_kecamatan         = $input->alamat_kecamatan;
                $staff->alamat_kodepos           = $input->alamat_kodepos;
                $staff->alamat_kota              = $input->alamat_kota;
                $staff->alamat_provinsi          = $input->alamat_provinsi;
                $staff->alamat_latitude          = $input->alamat_latitude;
                $staff->alamat_longitude         = $input->alamat_longitude;
                $staff->id_agama                 = $input->id_agama;
                $staff->npwp_ptk                 = $input->npwp_ptk;
                $staff->nm_wajib_pajak_ptk       = $input->nm_wajib_pajak_ptk;
                $staff->kewarganegaraan          = $input->kewarganegaraan;
                $staff->status_kawin             = $input->status_kawin;
                $staff->nm_pasangan_ptk          = $input->nm_pasangan_ptk;
                $staff->nip_pasangan_ptk         = $input->nip_pasangan_ptk;
                $staff->id_jenis_pekerjaan_pasangan_ptk  = $input->id_jenis_pekerjaan_pasangan_ptk;
                // $staff->nip_ptk                  = $input->nip_ptk;

                //section kepegawaian
                $staff->id_jenis_kepegawaian     = $input->id_jenis_kepegawaian;
                $staff->nip_staff                 = $input->nip_staff;
                $staff->niy_nigk_ptk             = $input->niy_nigk_ptk;
                $staff->nuptk                    = $input->nuptk;
                $staff->id_jenis_ptk             = $input->id_jenis_ptk;
                $staff->nomor_sk_pengangkatan    = $input->nomor_sk_pengangkatan;
                $staff->tgl_sk_pengangkatan       = date_format(date_create($input->tgl_sk_pengangkatan),"Y-m-d");;
                $staff->id_jenis_lembaga_pengangkat  = $input->id_jenis_lembaga_pengangkat;
                $staff->nomor_sk_cpns            = $input->nomor_sk_cpns;
                $staff->tgl_mulai_pns            = date_format(date_create($input->tgl_mulai_pns),"Y-m-d");;
                $staff->golongan_ptk             = $input->golongan_ptk;
                $staff->id_jenis_sumber_gaji     = $input->id_jenis_sumber_gaji;
                $staff->nomor_kartu_pegawai      = $input->nomor_kartu_pegawai;
                $staff->nomor_kartu_pasangan     = $input->nomor_kartu_pasangan;    

                //section kompetensi khusus
                $staff->is_lisensi_kepsek        = $input->is_lisensi_kepsek;
                $staff->id_jenis_keahlian_lab    = $input->id_jenis_keahlian_lab;
                $staff->is_keahlian_braile       = $input->is_keahlian_braile;
                $staff->is_keahlian_bahasa_isyarat   = $input->is_keahlian_bahasa_isyarat;

                //section kontak
                $staff->nomor_telp               = $input->nomor_telp;
                $staff->nomor_hp                 = $input->nomor_hp;
                $staff->email                    = $input->email;


                //section penugasan
                $staff->is_sekolah_induk         = $input->is_sekolah_induk;
                $staff->tgl_sk_penugasan         = date_format(date_create($input->tgl_sk_penugasan),"Y-m-d");;
                $staff->nomor_sk_penugasan       = $input->nomor_sk_penugasan;
                $staff->created_by               = $input->auth_data->pengguna->id_pengguna;
                $staff->created_at               = $now;
                $staff->save();

                $rolePengguna                           = new RolePengguna;
                $rolePengguna->id_role                  = 15;
                $rolePengguna->id_pengguna              = $pengguna->id_pengguna;
                $rolePengguna->keterangan_role_pengguna = "Input Sumber Daya";
                $rolePengguna->is_aktif                 = 1;
                $rolePengguna->created_by               = $input->auth_data->pengguna->id_pengguna;
                $rolePengguna->save();
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'tendik/input-tendik',
                    'message' => 'Save Tendik Successfully'
                ];
            }
            elseif($mode == 'edit') {
                // get id_pengguna
                $staff = Staff::select('id_pengguna')
                    ->where('id_staff','=',$id)
                    ->first();

                $id_pengguna = $staff->id_pengguna;

                // make object to find id
                $pengguna                           = Pengguna::find($id_pengguna);
                $pengguna->id_status_pengguna       = $input->id_status_pengguna;
                $pengguna->nm_pengguna              = $input->nm_pengguna;
                $pengguna->email_pengguna           = $input->email;
                $pengguna->nomor_hp_pengguna        = $input->nomor_hp;
                $pengguna->gelar_depan              = $input->gelar_depan;
                $pengguna->gelar_belakang           = $input->gelar_belakang;

                // apabila ada pergantian nip staff
                if($pengguna->username != $input->nip_staff){
                    $pengguna->username                 = $input->nip_staff;
                    $pengguna->password                 = Hash::make($input->nip_staff);
                    $pengguna->must_change_password     = 1;
                }

                $pengguna->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $pengguna->updated_at               = $now;
                $pengguna->save();

                // make object to find id
                $staff                           = Staff::find($id);
                /*$staff->id_jabatan_pegawai       = $input->id_jabatan_pegawai;*/
                $staff->id_unit_kerja            = $input->id_unit_kerja;
                $staff->jenis_jabatan            = $input->jenis_jabatan;
                $staff->nip_staff                = $input->nip_staff;
                $staff->nik_ptk                  = $input->nik_ptk;
                $staff->jenis_kelamin            = $input->jenis_kelamin;
                $staff->id_kota_lahir            = $input->id_kota_lahir;
                $staff->tgl_lahir                = date_format(date_create($input->tgl_lahir),"Y-m-d");
                $staff->nm_ibu_kandung           = $input->nm_ibu_kandung;
                $staff->alamat_jalan             = $input->alamat_jalan;
                $staff->alamat_rt                = $input->alamat_rt;
                $staff->alamat_rw                = $input->alamat_rw;
                $staff->alamat_dusun             = $input->alamat_dusun;
                $staff->alamat_kelurahan         = $input->alamat_kelurahan;
                $staff->alamat_kecamatan         = $input->alamat_kecamatan;
                $staff->alamat_kodepos           = $input->alamat_kodepos;
                $staff->alamat_kota              = $input->alamat_kota;
                $staff->alamat_provinsi          = $input->alamat_provinsi;
                $staff->alamat_latitude          = $input->alamat_latitude;
                $staff->alamat_longitude         = $input->alamat_longitude;
                $staff->id_agama                 = $input->id_agama;
                $staff->npwp_ptk                 = $input->npwp_ptk;
                $staff->nm_wajib_pajak_ptk       = $input->nm_wajib_pajak_ptk;
                $staff->kewarganegaraan          = $input->kewarganegaraan;
                $staff->status_kawin             = $input->status_kawin;
                $staff->nm_pasangan_ptk          = $input->nm_pasangan_ptk;
                $staff->nip_pasangan_ptk         = $input->nip_pasangan_ptk;
                $staff->id_jenis_pekerjaan_pasangan_ptk  = $input->id_jenis_pekerjaan_pasangan_ptk;
                // $staff->nip_ptk                  = $input->nip_ptk;

                //section kepegawaian
                $staff->id_jenis_kepegawaian     = $input->id_jenis_kepegawaian;
                // $staff->nip_staff                 = $input->nip_staff;
                $staff->niy_nigk_ptk             = $input->niy_nigk_ptk;
                $staff->nuptk                    = $input->nuptk;
                $staff->id_jenis_ptk             = $input->id_jenis_ptk;
                $staff->nomor_sk_pengangkatan    = $input->nomor_sk_pengangkatan;
                $staff->tgl_sk_pengangkatan       = date_format(date_create($input->tgl_sk_pengangkatan),"Y-m-d");;
                $staff->id_jenis_lembaga_pengangkat  = $input->id_jenis_lembaga_pengangkat;
                $staff->nomor_sk_cpns            = $input->nomor_sk_cpns;
                $staff->tgl_mulai_pns            = date_format(date_create($input->tgl_mulai_pns),"Y-m-d");;
                $staff->golongan_ptk             = $input->golongan_ptk;
                $staff->id_jenis_sumber_gaji     = $input->id_jenis_sumber_gaji;
                $staff->nomor_kartu_pegawai      = $input->nomor_kartu_pegawai;
                $staff->nomor_kartu_pasangan     = $input->nomor_kartu_pasangan;    

                //section kompetensi khusus
                $staff->is_lisensi_kepsek        = $input->is_lisensi_kepsek;
                $staff->id_jenis_keahlian_lab    = $input->id_jenis_keahlian_lab;
                $staff->is_keahlian_braile       = $input->is_keahlian_braile;
                $staff->is_keahlian_bahasa_isyarat   = $input->is_keahlian_bahasa_isyarat;

                //section kontak
                $staff->nomor_telp               = $input->nomor_telp;
                $staff->nomor_hp                 = $input->nomor_hp;
                $staff->email                    = $input->email;


                //section penugasan
                $staff->is_sekolah_induk         = $input->is_sekolah_induk;
                $staff->tgl_sk_penugasan         = date_format(date_create($input->tgl_sk_penugasan),"Y-m-d");;
                $staff->nomor_sk_penugasan       = $input->nomor_sk_penugasan;
                $staff->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $staff->updated_at               = $now;
                $staff->save();


                return [
                    'status' => 300, // SUCCESS AND LOAD CONTENT
                    'message' => 'Update Data Successfully'
                ];
            }
            elseif($mode == 'delete') {
                    // make object to find id
                    $staff               = Staff::find($id);

                    if($guruPiket = GuruPiket::where('id_pengguna',$staff->id_pengguna)->first()) {
                        return [
                            'status' => 300, // SUCCESS AND LOAD TABLE
                            'message' => 'Tendik Sudah Di Plot Guru Piket'
                        ]; 
                    }
                    else {
                        $pengguna               = Pengguna::find($staff->id_pengguna);
                        $pengguna->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                        $pengguna->save();

                        $pengguna->delete();

                        $staff->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                        $staff->save();

                        $staff->delete();
                    }

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Tendik Successfully'
                    ];
            }
        }
    }

}