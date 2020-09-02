<?php

namespace App\Http\Controllers\SumberDaya\Guru;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\Guru as Guru;
use App\Models\PengampuMp as PengampuMp;
use App\Models\Kota as Kota;
use App\Models\Agama as Agama;
use App\Models\Provinsi as Provinsi;
use App\Models\JenisPekerjaan as JenisPekerjaan;
use App\Models\JenisKepegawaian as JenisKepegawaian;
use App\Models\JenisPtk as JenisPtk;
use App\Models\JenisKeahlianLab as JenisKeahlianLab;
use App\Models\JenisSumberGaji as JenisSumberGaji;
use App\Models\JenisLembagaPengangkat as JenisLembagaPengangkat;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibGuru;

use Illuminate\Support\Facades\Hash;

use Auth;
use DB;
use Session;
use Validator;

class InputGuruController extends BaseController{

    public function viewInputGuru(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/guru/input-guru/view-input-guru',compact('auth_data'));

    }

    public function getKota($id_provinsi)
    {
        $kota = Kota::where('id_provinsi','=',$id_provinsi)->get();
        return response()->json($kota);
    }

    public function addInputGuru(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_status_aktif_guru = LibDataSumberDaya::fetchDataStatusAktifGuru($auth_data);

        /*$controller = App::make('\App\Http\Controllers\SumberDaya\DataSumberDaya\JabatanPegawaiController');
        $data_jabatan_pegawai = $controller->fetchDataJabatanPegawai($auth_data);*/

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_guru = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        $kota = Kota::where('kota.is_aktif','=',1)->get();
        $provinsi = Provinsi::where('provinsi.is_aktif','=',1)->get();
        $agama = Agama::get();
        $pegawai = JenisKepegawaian::get();
        // dd($pegawai);
        $pekerjaan = JenisPekerjaan::get();
        // dd($pekerjaan);
        $ptk = JenisPtk::get();
        $pengangkat = JenisLembagaPengangkat::get();
        $gaji = JenisSumberGaji::get();
        $lab = JenisKeahlianLab::get();


        /*return view('sumber-daya/guru/input-guru/add-input-guru',compact('auth_data','data_status_aktif_guru','data_jabatan_pegawai','data_unit_kerja','id_guru'));*/

        return view('sumber-daya/guru/input-guru/add-input-guru',compact('auth_data','data_status_aktif_guru','data_unit_kerja','id_guru','kota','provinsi','agama','pegawai','pekerjaan','ptk','pengangkat','gaji','lab'));

    }

    public function editInputGuru($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_status_aktif_guru = LibDataSumberDaya::fetchDataStatusAktifGuru($auth_data);

        /*$controller = App::make('\App\Http\Controllers\SumberDaya\DataSumberDaya\JabatanPegawaiController');
        $data_jabatan_pegawai = $controller->fetchDataJabatanPegawai($auth_data);*/

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $guru = LibGuru::fetchDataAllGuru($auth_data, $id);

        $kota = Kota::where('kota.is_aktif','=',1)->get();
        $provinsi = Provinsi::where('provinsi.is_aktif','=',1)->get();
        $agama = Agama::get();
        $pegawai = JenisKepegawaian::get();
        $pekerjaan = JenisPekerjaan::get();
        $ptk = JenisPtk::get();
        $pengangkat = JenisLembagaPengangkat::get();
        $gaji = JenisSumberGaji::get();
        $lab = JenisKeahlianLab::get();

        /*return view('sumber-daya/guru/input-guru/edit-input-guru',compact('auth_data','data_status_aktif_guru','data_jabatan_pegawai','data_unit_kerja','data_guru'));*/

        return view('sumber-daya/guru/input-guru/edit-input-guru',compact('auth_data','data_status_aktif_guru','data_unit_kerja','guru','kota','provinsi','agama','pegawai','pekerjaan','ptk','pengangkat','gaji','lab'));

    }

    public function datatablesInputGuru(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = LibGuru::fetchDataAllGuru($auth_data,null,"1");

        return Datatables::of($list_data)
                ->addColumn('nm_pengguna', function($item){
                    if( ! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    }
                    elseif( ! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;   
                    }
                    elseif( ! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;   
                    }
                    else {
                        return $item->nm_pengguna; 
                    }
                })
                ->addColumn('jml_mengajar_semester_aktif', function($item){
                    if( ! empty($item->jml_mengajar_semester_aktif)) {
                        return $item->jml_mengajar_semester_aktif;
                    }
                    else {
                        return 0;
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_guru
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionInputGuru(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_pengguna'           => 'required',
            'nip_guru'              => 'required',
            /*'id_jabatan_pegawai'    => 'required',*/
            'id_unit_kerja'         => 'required',
            'jenis_jabatan'         => 'required',
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
                $pengguna->username                 = $input->nip_guru;
                $pengguna->password                 = Hash::make($input->nip_guru);
                $pengguna->must_change_password     = 1;
                $pengguna->status_join_table        = 2;
                $pengguna->email_pengguna           = $input->email;
                $pengguna->nomor_hp_pengguna        = $input->nomor_hp;
                $pengguna->created_by               = $input->auth_data->pengguna->id_pengguna;
                $pengguna->created_at               = $now;
                $pengguna->gelar_depan              = $input->gelar_depan;
                $pengguna->gelar_belakang           = $input->gelar_belakang;
                $pengguna->save();

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $guru                           = new Guru;
                $guru->id_guru                  = $id;
                $guru->id_pengguna              = $pengguna->id_pengguna;
                /*$guru->id_jabatan_pegawai       = $input->id_jabatan_pegawai;*/
                $guru->id_unit_kerja            = $input->id_unit_kerja;
                $guru->jenis_jabatan            = $input->jenis_jabatan;
                $guru->nik_ptk                  = $input->nik_ptk;
                $guru->jenis_kelamin            = $input->jenis_kelamin;
                $guru->id_kota_lahir            = $input->id_kota_lahir;
                $guru->tgl_lahir                = date_format(date_create($input->tgl_lahir),"Y-m-d");
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
                // $guru->nip_ptk                  = $input->nip_ptk;

                //section kepegawaian
                $guru->id_jenis_kepegawaian     = $input->id_jenis_kepegawaian;
                $guru->nip_guru                 = $input->nip_guru;
                $guru->niy_nigk_ptk             = $input->niy_nigk_ptk;
                $guru->nuptk                    = $input->nuptk;
                $guru->id_jenis_ptk             = $input->id_jenis_ptk;
                $guru->nomor_sk_pengangkatan    = $input->nomor_sk_pengangkatan;
                $guru->tgl_sk_pengangkatan       = date_format(date_create($input->tgl_sk_pengangkatan),"Y-m-d");;
                $guru->id_jenis_lembaga_pengangkat  = $input->id_jenis_lembaga_pengangkat;
                $guru->nomor_sk_cpns            = $input->nomor_sk_cpns;
                $guru->tgl_mulai_pns            = date_format(date_create($input->tgl_mulai_pns),"Y-m-d");;
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
                $guru->tgl_sk_penugasan         = date_format(date_create($input->tgl_sk_penugasan),"Y-m-d");;
                $guru->nomor_sk_penugasan       = $input->nomor_sk_penugasan;
                $guru->created_by               = $input->auth_data->pengguna->id_pengguna;
                $guru->created_at               = $now;
                $guru->save();

                $rolePengguna                           = new RolePengguna;
                $rolePengguna->id_role                  = 2;
                $rolePengguna->id_pengguna              = $pengguna->id_pengguna;
                $rolePengguna->keterangan_role_pengguna = "Input Sumber Daya";
                $rolePengguna->is_aktif                 = 1;
                $rolePengguna->created_by               = $input->auth_data->pengguna->id_pengguna;
                $rolePengguna->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'guru/input-guru',
                    'message' => 'Save Guru successfully'
                ];
            }
            elseif($mode == 'edit') {
                // get id_pengguna
                $guru = Guru::select('id_pengguna')
                    ->where('id_guru','=',$id)
                    ->first();

                $id_pengguna = $guru->id_pengguna;

                // make object to find id
                $pengguna                           = Pengguna::find($id_pengguna);
                $pengguna->id_status_pengguna       = $input->id_status_pengguna;
                $pengguna->nm_pengguna              = $input->nm_pengguna;

                // apabila ada pergantian nip guru
                if($pengguna->username != $input->nip_guru){
                    $pengguna->username                 = $input->nip_guru;
                    $pengguna->password                 = Hash::make($input->nip_guru);
                    $pengguna->must_change_password     = 1;
                }
                $pengguna->email_pengguna           = $input->email;
                $pengguna->nomor_hp_pengguna        = $input->nomor_hp;
                $pengguna->gelar_depan              = $input->gelar_depan;
                $pengguna->gelar_belakang           = $input->gelar_belakang;
                $pengguna->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $pengguna->updated_at               = $now;
                $pengguna->save();

                // make object to find id
                $guru                           = Guru::find($id);
                /*$guru->id_jabatan_pegawai       = $input->id_jabatan_pegawai;*/
                $guru->id_unit_kerja            = $input->id_unit_kerja;
                $guru->jenis_jabatan            = $input->jenis_jabatan;
                $guru->nip_guru                 = $input->nip_guru;
                $guru->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $guru->updated_at               = $now;
                $guru->nik_ptk                  = $input->nik_ptk;
                $guru->jenis_kelamin            = $input->jenis_kelamin;
                $guru->id_kota_lahir            = $input->id_kota_lahir;
                $guru->tgl_lahir                = date_format(date_create($input->tgl_lahir),"Y-m-d");
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
                // $guru->nip_ptk                  = $input->nip_ptk;

                //section kepegawaian
                $guru->id_jenis_kepegawaian     = $input->id_jenis_kepegawaian;
                // $guru->nip_guru                 = $input->nip_guru;
                $guru->niy_nigk_ptk             = $input->niy_nigk_ptk;
                $guru->nuptk                    = $input->nuptk;
                $guru->id_jenis_ptk             = $input->id_jenis_ptk;
                $guru->nomor_sk_pengangkatan    = $input->nomor_sk_pengangkatan;
                $guru->tgl_sk_pengangkatan       = date_format(date_create($input->tgl_sk_pengangkatan),"Y-m-d");;
                $guru->id_jenis_lembaga_pengangkat  = $input->id_jenis_lembaga_pengangkat;
                $guru->nomor_sk_cpns            = $input->nomor_sk_cpns;
                $guru->tgl_mulai_pns            = date_format(date_create($input->tgl_mulai_pns),"Y-m-d");;
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
                $guru->tgl_sk_penugasan         = date_format(date_create($input->tgl_sk_penugasan),"Y-m-d");;
                $guru->nomor_sk_penugasan       = $input->nomor_sk_penugasan;
                $guru->save();


                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'guru/input-guru',
                    'message' => 'Update Guru successfully'
                ];
            }
            elseif($mode == 'delete') {
                if($pengampuMp = PengampuMp::where('id_guru',$id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Guru'
                    ]; 
                }
                else {
                    // make object to find id
                    $guru                   = Guru::find($id);

                    $pengguna               = Pengguna::find($guru->id_pengguna);
                    $pengguna->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $pengguna->save();

                    $pengguna->delete();

                    $guru->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $guru->save();

                    $guru->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Guru successfully!'
                    ];
                }
            }
        }
    }

}