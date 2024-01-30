<?php

namespace App\Http\Controllers\PPDB\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Penerimaan as Penerimaan;
use App\Models\CalonSiswaBaru as CalonSiswaBaru;
use App\Models\Semester as Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

use Auth;
use DB;
use Session;
use Validator;

class PenerimaanController extends BaseController
{

    /**
     * Instantiate a new PenerimaanController instance.
     */
    /*public function __construct()
    {
        setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
    }*/

    public function viewPenerimaan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('ppdb/pendaftaran/penerimaan/view-penerimaan', compact('auth_data'));
    }

    public function addPenerimaan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $data_semester_tahun = LibDataAkademik::fetchDataTahunSemester($auth_data);

        $data_semester_nama = LibDataAkademik::fetchDataNmSemester($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_penerimaan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('ppdb/pendaftaran/penerimaan/add-penerimaan', compact('auth_data', 'data_jalur', 'data_semester_tahun', 'data_semester_nama', 'id_penerimaan'));
    }

    public function editPenerimaan($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $data_semester_tahun = LibDataAkademik::fetchDataTahunSemester($auth_data);

        $data_semester_nama = LibDataAkademik::fetchDataNmSemester($auth_data);

        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        // convert format date
        $tgl_awal_registrasi = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_awal_registrasi));
        $tgl_akhir_registrasi = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_akhir_registrasi));
        $tgl_awal_verifikasi = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_awal_verifikasi));
        $tgl_akhir_verifikasi = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_akhir_verifikasi));
        $tgl_awal_voucher = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_awal_voucher));
        $tgl_akhir_voucher = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_akhir_voucher));
        $tgl_penetapan = strftime("%d %B %Y", strtotime($data_penerimaan->tgl_penetapan));
        $tgl_pengumuman = strftime("%d %B %Y %T", strtotime($data_penerimaan->tgl_pengumuman));

        return view('ppdb/pendaftaran/penerimaan/edit-penerimaan', compact('auth_data', 'data_jalur', 'data_semester_tahun', 'data_semester_nama', 'data_penerimaan', 'tgl_awal_registrasi', 'tgl_akhir_registrasi', 'tgl_awal_verifikasi', 'tgl_akhir_verifikasi', 'tgl_awal_voucher', 'tgl_akhir_voucher', 'tgl_penetapan', 'tgl_pengumuman'));
    }

    public function datatablesPenerimaan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibPenerimaan::fetchDataPenerimaanAllJenisPenerimaan($auth_data);

        return Datatables::of($list_data)
            ->addColumn('nm_jalur', function ($item) {
                if (!empty($item->nm_jalur)) {
                    return $item->nm_jalur;
                } else {
                    return "-";
                }
            })
            ->addColumn('status_aktif', function ($item) {
                if ($item->is_aktif == 0) {
                    return "Non-Aktif";
                } else {
                    return "Aktif";
                }
            })
            ->editColumn('biaya_daftar_ulang', function ($item) {
                return 'Rp' . number_format($item->biaya_daftar_ulang);
            })
            ->addColumn('pengumuman', function ($item) {
                // mengambil waktu sekarang
                $now = Carbon::now(env('APP_TIMEZONE', 'Asia/Jakarta'));

                if (date_format(date_create($item->tgl_pengumuman), "Y-m-d H:i:s") <= $now) {
                    return "Publish";
                } else {
                    return "-";
                }
            })
            ->addColumn('jenis_penerimaan', function ($item) {
                if ($item->jenis_penerimaan == 1) {
                    return "Siswa Baru";
                } else {
                    return "Siswa Lama";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_penerimaan
                );
                return $data;
            })
            ->make(true);
    }


    // Action POST
    public function actionPenerimaan(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'tahun_penerimaan'          => 'required',
            'id_jalur'                  => 'required',
            'nm_penerimaan'             => 'required',
            'gelombang_penerimaan'      => 'required',
            'nm_semester_penerimaan'    => 'required',
            //'jml_pilihan_jurusan'       => 'required',
            'tgl_awal_registrasi'       => 'required',
            'tgl_akhir_registrasi'      => 'required',
            'tgl_awal_verifikasi'       => 'required',
            'tgl_akhir_verifikasi'      => 'required',
            'tgl_awal_voucher'          => 'required',
            'tgl_akhir_voucher'         => 'required',
            'tgl_penetapan'             => 'required',
            'tgl_pengumuman'            => 'required',
            'is_pendaftaran_online'     => 'required',
            'is_verifikasi'             => 'required',
            'is_bayar_voucher'          => 'required',
            'nomor_rekening_transfer'   => 'required',
            // 'biaya_daftar_ulang'          => 'required',
            'jenis_penerimaan'          => 'required',
            'is_aktif'                  => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode != 'delete') {
                // get id_semester from tahun and nama semester
                $semester = Semester::select('id_semester')
                    ->where('thn_akademik_semester', '=', $input->tahun_penerimaan)
                    ->where('nm_semester', '=', $input->nm_semester_penerimaan)
                    ->where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                    ->first();

                // apabila jenis penerimaan untuk siswa lama sebelum siakad
                if ($input->jenis_penerimaan == 2) {
                    $id_semester = 0;
                    $id_jalur = 0;
                } elseif ($input->jenis_penerimaan == 1) {
                    $id_semester = $semester->id_semester;
                    $id_jalur = $input->id_jalur;
                }
            }

            // ACTION ADD
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $penerimaan                             = new Penerimaan;
                $penerimaan->id_penerimaan              = $id;
                $penerimaan->id_semester                = $id_semester;
                $penerimaan->tahun_penerimaan           = $input->tahun_penerimaan;
                $penerimaan->id_jalur                   = $id_jalur;
                $penerimaan->nm_penerimaan              = $input->nm_penerimaan;
                $penerimaan->gelombang_penerimaan       = $input->gelombang_penerimaan;
                $penerimaan->nm_semester_penerimaan     = $input->nm_semester_penerimaan;
                $penerimaan->jml_pilihan_jurusan        = $input->jml_pilihan_jurusan;
                $penerimaan->tgl_awal_registrasi        = date_format(date_create($input->tgl_awal_registrasi), "Y-m-d");
                $penerimaan->tgl_akhir_registrasi       = date_format(date_create($input->tgl_akhir_registrasi), "Y-m-d");
                $penerimaan->tgl_awal_verifikasi        = date_format(date_create($input->tgl_awal_verifikasi), "Y-m-d");
                $penerimaan->tgl_akhir_verifikasi       = date_format(date_create($input->tgl_akhir_verifikasi), "Y-m-d");
                $penerimaan->tgl_awal_voucher           = date_format(date_create($input->tgl_awal_voucher), "Y-m-d");
                $penerimaan->tgl_akhir_voucher          = date_format(date_create($input->tgl_akhir_voucher), "Y-m-d");
                $penerimaan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan), "Y-m-d");
                $penerimaan->tgl_pengumuman             = date_format(date_create($input->tgl_pengumuman), "Y-m-d H:i:s");
                $penerimaan->is_pendaftaran_online      = $input->is_pendaftaran_online;
                $penerimaan->is_verifikasi              = $input->is_verifikasi;
                $penerimaan->is_bayar_voucher           = $input->is_bayar_voucher;
                $penerimaan->nomor_rekening_transfer    = $input->nomor_rekening_transfer;
                $penerimaan->jenis_penerimaan           = $input->jenis_penerimaan;
                // $penerimaan->biaya_daftar_ulang         = $input->biaya_daftar_ulang;
                $penerimaan->is_aktif                   = $input->is_aktif;
                $penerimaan->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                $penerimaan->created_by                 = $input->auth_data->pengguna->id_pengguna;
                $penerimaan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pendaftaran/penerimaan',
                    'message' => 'Save Penerimaan Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $penerimaan                             = Penerimaan::find($id);
                $penerimaan->id_semester                = $id_semester;
                $penerimaan->tahun_penerimaan           = $input->tahun_penerimaan;
                $penerimaan->id_jalur                   = $id_jalur;
                $penerimaan->nm_penerimaan              = $input->nm_penerimaan;
                $penerimaan->gelombang_penerimaan       = $input->gelombang_penerimaan;
                $penerimaan->nm_semester_penerimaan     = $input->nm_semester_penerimaan;
                $penerimaan->jml_pilihan_jurusan        = $input->jml_pilihan_jurusan;
                $penerimaan->tgl_awal_registrasi        = date_format(date_create($input->tgl_awal_registrasi), "Y-m-d");
                $penerimaan->tgl_akhir_registrasi       = date_format(date_create($input->tgl_akhir_registrasi), "Y-m-d");
                $penerimaan->tgl_awal_verifikasi        = date_format(date_create($input->tgl_awal_verifikasi), "Y-m-d");
                $penerimaan->tgl_akhir_verifikasi       = date_format(date_create($input->tgl_akhir_verifikasi), "Y-m-d");
                $penerimaan->tgl_awal_voucher           = date_format(date_create($input->tgl_awal_voucher), "Y-m-d");
                $penerimaan->tgl_akhir_voucher          = date_format(date_create($input->tgl_akhir_voucher), "Y-m-d");
                $penerimaan->tgl_penetapan              = date_format(date_create($input->tgl_penetapan), "Y-m-d");
                $penerimaan->tgl_pengumuman             = date_format(date_create($input->tgl_pengumuman), "Y-m-d H:i:s");
                $penerimaan->is_pendaftaran_online      = $input->is_pendaftaran_online;
                $penerimaan->is_verifikasi              = $input->is_verifikasi;
                $penerimaan->is_bayar_voucher           = $input->is_bayar_voucher;
                $penerimaan->nomor_rekening_transfer    = $input->nomor_rekening_transfer;
                $penerimaan->jenis_penerimaan           = $input->jenis_penerimaan;
                $penerimaan->biaya_daftar_ulang         = $input->biaya_daftar_ulang;
                $penerimaan->is_aktif                   = $input->is_aktif;
                $penerimaan->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $penerimaan->updated_at                 = $now;
                $penerimaan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pendaftaran/penerimaan',
                    'message' => 'Update Penerimaan Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($calonSiswaBaru = CalonSiswaBaru::where('id_penerimaan', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Penerimaan'
                    ];
                } else {
                    // make object to find id
                    $penerimaan               = Penerimaan::find($id);
                    $penerimaan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $penerimaan->save();

                    $penerimaan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Penerimaan Successfully'
                    ];
                }
            }
        }
    }
}
