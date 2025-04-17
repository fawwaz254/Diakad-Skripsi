<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan;

use App\Imports\DataImportExcel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JenisRuangan as JenisRuangan;
use App\Models\Ruangan as Ruangan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;
use Excel;

class JenisRuanganController extends BaseController
{

    public function viewJenisRuangan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/data-sarpras-ruangan/jenis-ruangan/view-jenis-ruangan', compact('auth_data'));
    }

    public function addJenisRuangan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_jenis_ruangan = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/data-sarpras-ruangan/jenis-ruangan/add-jenis-ruangan', compact('auth_data', 'id_jenis_ruangan'));
    }

    public function editJenisRuangan($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_ruangan = LibDataSarpras::fetchDataJenisRuangan($auth_data, $id);

        return view('sarana-prasarana/data-sarpras-ruangan/jenis-ruangan/edit-jenis-ruangan', compact('auth_data', 'data_jenis_ruangan'));
    }

    public function datatablesJenisRuangan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataJenisRuangan($auth_data);

        return Datatables::of($list_data)
            ->addColumn('tipe_ruangan', function ($item) {
                if ($item->tipe_ruangan == 1) {
                    return "Teori";
                } else if ($item->tipe_ruangan == 2) {
                    return "Pendukung";
                } else if ($item->tipe_ruangan == 3) {
                    return "Praktik";
                } else {
                    return "Lain-lain";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jenis_ruangan
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionJenisRuangan(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jenis_ruangan' => 'required',
            'tipe_ruangan' => 'required'
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

                $jenisRuangan                       = new JenisRuangan;
                $jenisRuangan->id_jenis_ruangan     = $id;
                $jenisRuangan->nm_jenis_ruangan     = $input->nm_jenis_ruangan;
                $jenisRuangan->tipe_ruangan         = $input->tipe_ruangan;
                $jenisRuangan->id_sekolah           = $input->auth_data->pengguna->id_sekolah;
                $jenisRuangan->created_by           = $input->auth_data->pengguna->id_pengguna;
                $jenisRuangan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-ruangan/jenis-ruangan',
                    'message' => 'Save Jenis Ruangan Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $jenisRuangan                       = JenisRuangan::find($id);
                $jenisRuangan->nm_jenis_ruangan     = $input->nm_jenis_ruangan;
                $jenisRuangan->tipe_ruangan         = $input->tipe_ruangan;
                $jenisRuangan->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $jenisRuangan->updated_at           = $now;
                $jenisRuangan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-ruangan/jenis-ruangan',
                    'message' => 'Update Jenis Ruangan Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($ruangan = Ruangan::where('id_jenis_ruangan', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jenis Ruangan'
                    ];
                } else {
                    // make object to find id
                    $jenisRuangan               = JenisRuangan::find($id);
                    $jenisRuangan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jenisRuangan->save();

                    $jenisRuangan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jenis Ruangan Successfully'
                    ];
                }
            }
        }
    }

    public function importExcel(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/data-sarpras-ruangan/jenis-ruangan/import-excel', compact('auth_data'));
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

                            if (empty($value->nama_jenis_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data jenis ruangan gagal, ada nama jenis ruangan yang kosong'
                                ];
                            }

                            if (empty($value->tipe_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data jenis ruangan gagal, ada tipe ruangan yang kosong'
                                ];
                            }

                            if (!(ucwords($value->tipe_ruangan) == 'Kelas' || ucwords($value->tipe_ruangan) == 'Non-Kelas')) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data jenis ruangan gagal, tipe ruangan yang ada hanya Kelas  / Non-Kelas'
                                ];
                            }

                            $tipe_ruangan = ucwords($value->tipe_ruangan);

                            if ($tipe_ruangan == 'Kelas') {
                                $tipe_ruangan = 1;
                            } else {
                                $tipe_ruangan = 2;
                            }

                            $data                                = new JenisRuangan;
                            $data->id_jenis_ruangan              = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $data->nm_jenis_ruangan              = $value->nama_jenis_ruangan;
                            $data->tipe_ruangan                  = $tipe_ruangan;
                            $data->id_sekolah                    = $input->auth_data->pengguna->id_sekolah;
                            $data->created_by                    = $input->auth_data->pengguna->id_pengguna;
                            $data->save();
                        }

                        DB::commit();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'data-sarpras-ruangan/jenis-ruangan',
                            'message' => 'Import Jenis Ruangan Successfully'
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
}
