<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use DB;
use Auth;

use Excel;
use Session;

use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Imports\DataImportExcel;
use Yajra\Datatables\Datatables;
use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Models\RekananMagang as RekananMagang;
use Illuminate\Routing\Controller as BaseController;
use App\Models\PengambilanMagang as PengajuanSiswaMagang;

class RekananMagangController extends BaseController
{
    public function viewRekananMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/magang-siswa/rekanan-magang/view-rekanan-magang', compact('auth_data'));
    }

    public function importExcel(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/magang-siswa/rekanan-magang/import-excel', compact('auth_data'));
    }

    public function importExcelAction(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'file-excel' => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($request->hasFile('file-excel')) {

                $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
                $data = $data[0];

                if (count($data)) {

                    DB::beginTransaction();

                    try {

                        foreach ($data as $key => $value) {

                            $value = (object) $value;

                            if (empty($value->nama_rekanan_magang)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data rekanan magang gagal, ada nama rekanan magang yang kosong'
                                ];
                            }

                            if (empty($value->alamat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data rekanan magang gagal, ada alamat yang kosong'
                                ];
                            }

                            if (empty($value->tanggal_awal_kerja_sama)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data rekanan magang gagal, ada tanggal awal kerja yang kosong'
                                ];
                            }

                            if (empty($value->tanggal_akhir_kerja_sama)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data rekanan magang gagal, ada tanggal akhir kerja yang kosong'
                                ];
                            }

                            if (empty($value->kuota_magang)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data rekanan magang gagal, ada kuota magang yang kosong'
                                ];
                            }

                            if (empty($value->contact_person_magang)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data rekanan magang gagal, ada contact person magang yang kosong'
                                ];
                            }

                            $data                                = new RekananMagang;
                            $data->id_rekanan_magang             = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $data->nm_rekanan_magang             = $value->nama_rekanan_magang;
                            if (!empty($value->no_telepon)) {
                                $data->nomor_telp_rekanan_magang     = $value->no_telepon;
                            }
                            if (!empty($value->no_hp)) {
                                $data->nomor_hp_rekanan_magang       = $value->no_hp;
                            }
                            $data->alamat_rekanan_magang         = $value->alamat;
                            $data->tgl_awal_kerjasama            = date("Y-m-d", strtotime($value->tanggal_awal_kerja_sama));
                            $data->tgl_akhir_kerjasama           = date("Y-m-d", strtotime($value->tanggal_akhir_kerja_sama));
                            $data->kuota_rekanan_magang          = $value->kuota_magang;
                            $data->contact_person_rekanan_magang = $value->contact_person_magang;
                            $data->id_sekolah                    = $input->auth_data->pengguna->id_sekolah;
                            $data->created_by                    = $input->auth_data->pengguna->id_pengguna;
                            $data->save();
                        }

                        DB::commit();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'magang-siswa/rekanan-magang',
                            'message' => 'Import Rekanan Magang Successfully'
                        ];
                    } catch (\Exception $e) {

                        DB::rollback();

                        return [
                            'status'    => 203, // GAGAL
                            'message'       => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
                        ];
                    }
                } else {

                    return [
                        'status'    => 300, // FAILED
                        'message'   => "File excel anda kosong"
                    ];
                }
            } else {
                return [
                    'status'    => 300, // FAILED
                    'message'   => "File Excel tidak ditemukan"
                ];
            }
        }
    }

    public function editRekananMagang($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data, $id);

        $tgl_mulai = strftime("%d %B %Y", strtotime($data_rekanan_magang->tgl_awal_kerjasama));
        $tgl_selesai = strftime("%d %B %Y", strtotime($data_rekanan_magang->tgl_akhir_kerjasama));

        return view('humas/magang-siswa/rekanan-magang/edit-rekanan-magang', compact('auth_data', 'data_rekanan_magang', 'tgl_mulai', 'tgl_selesai'));
    }
    public function datatablesRekananMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibMagangSiswa::fetchDataRekananMagang($auth_data);

        return Datatables::of($list_data)
            ->addColumn('tgl_mulai', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_awal_kerjasama));
            })
            ->addColumn('tgl_selesai', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_akhir_kerjasama));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_rekanan_magang
                );
                return $data;
            })
            ->make(true);
    }
    public function addRekananMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_rekanan_magang = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('humas/magang-siswa/rekanan-magang/add-rekanan-magang', compact('auth_data', 'id_rekanan_magang'));
    }

    public function actionRekananMagang(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_rekanan_magang' => 'required',
            'nomor_telp_rekanan_magang' => 'required',
            'nomor_hp_rekanan_magang' => 'required',
            'alamat_rekanan_magang' => 'required',
            'tgl_awal_kerjasama' => 'required',
            'tgl_akhir_kerjasama' => 'required',
            'kuota_rekanan_magang' => 'required',
            'contact_person_rekanan_magang' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $rekananMagang                                = new RekananMagang;
                $rekananMagang->id_rekanan_magang             = $id;
                $rekananMagang->nm_rekanan_magang             = $input->nm_rekanan_magang;
                $rekananMagang->nomor_telp_rekanan_magang     = $input->nomor_telp_rekanan_magang;
                $rekananMagang->nomor_hp_rekanan_magang       = $input->nomor_hp_rekanan_magang;
                $rekananMagang->alamat_rekanan_magang         = $input->alamat_rekanan_magang;
                $rekananMagang->tgl_awal_kerjasama            = date_format(date_create($input->tgl_awal_kerjasama), "Y-m-d");
                $rekananMagang->tgl_akhir_kerjasama           = date_format(date_create($input->tgl_akhir_kerjasama), "Y-m-d");
                $rekananMagang->contact_person_rekanan_magang = $input->contact_person_rekanan_magang;
                $rekananMagang->kuota_rekanan_magang          = $input->kuota_rekanan_magang;
                $rekananMagang->id_sekolah                    = $input->auth_data->pengguna->id_sekolah;
                $rekananMagang->created_by                    = $input->auth_data->pengguna->id_pengguna;
                $rekananMagang->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/rekanan-magang',
                    'message' => 'Save Rekanan Magang Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $rekananMagang                                = RekananMagang::find($id);
                $rekananMagang->nm_rekanan_magang             = $input->nm_rekanan_magang;
                $rekananMagang->nomor_telp_rekanan_magang     = $input->nomor_telp_rekanan_magang;
                $rekananMagang->nomor_hp_rekanan_magang       = $input->nomor_hp_rekanan_magang;
                $rekananMagang->alamat_rekanan_magang         = $input->alamat_rekanan_magang;
                $rekananMagang->tgl_awal_kerjasama            = date_format(date_create($input->tgl_awal_kerjasama), "Y-m-d");
                $rekananMagang->tgl_akhir_kerjasama           = date_format(date_create($input->tgl_akhir_kerjasama), "Y-m-d");
                $rekananMagang->contact_person_rekanan_magang = $input->contact_person_rekanan_magang;
                $rekananMagang->kuota_rekanan_magang          = $input->kuota_rekanan_magang;
                $rekananMagang->id_sekolah                    = $input->auth_data->pengguna->id_sekolah;
                $rekananMagang->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $rekananMagang->updated_at              = $now;
                $rekananMagang->save();
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/rekanan-magang',
                    'message' => 'Update Rekanan Magang Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($pengajuanMagang = PengajuanSiswaMagang::where('id_periode_magang', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Rekanan Magang'
                    ];
                } else {
                    // make object to find id
                    $rekananMagang                = RekananMagang::find($id);
                    $rekananMagang->deleted_by    = $input->auth_data->pengguna->id_pengguna;
                    $rekananMagang->save();

                    $rekananMagang->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Rekanan Magang Successfully'
                    ];
                }
            }
        }
    }
}
