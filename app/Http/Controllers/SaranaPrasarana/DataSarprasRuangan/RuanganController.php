<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasRuangan;

use App\Imports\DataImportExcel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JenisRuangan;
use App\Models\Gedung;
use App\Models\PemilikSarpras;
use App\Models\Ruangan as Ruangan;
use App\Models\RuanganKelas as RuanganKelas;
use App\Models\InventarisRuangan as InventarisRuangan;
use App\Models\JadwalKelasMp;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Models\Kelas;
use Auth;
use DB;
use Session;
use Validator;
use Excel;

class RuanganController extends BaseController
{

    public function viewRuangan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/data-sarpras-ruangan/ruangan/view-ruangan', compact('auth_data'));
    }

    public function addRuangan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_ruangan = LibDataSarpras::fetchDataJenisRuangan($auth_data);

        $data_gedung = LibDataSarpras::fetchDataGedung($auth_data);

        $data_pemilik_sarpras = LibDataSarpras::fetchDataPemilikSarpras($auth_data);
        $kelas = Kelas::all();
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_ruangan = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/data-sarpras-ruangan/ruangan/add-ruangan', compact('auth_data', 'data_jenis_ruangan', 'data_gedung', 'data_pemilik_sarpras', 'id_ruangan', 'kelas'));
    }

    public function editRuangan($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_ruangan = LibDataSarpras::fetchDataJenisRuangan($auth_data);

        $data_gedung = LibDataSarpras::fetchDataGedung($auth_data);

        $data_pemilik_sarpras = LibDataSarpras::fetchDataPemilikSarpras($auth_data);

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data, null, $id);

        $kelas = Kelas::all();

        return view('sarana-prasarana/data-sarpras-ruangan/ruangan/edit-ruangan', compact('auth_data', 'data_jenis_ruangan', 'data_gedung', 'data_pemilik_sarpras', 'data_ruangan', 'kelas'));
    }

    public function datatablesRuangan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataRuangan($auth_data);
        return Datatables::of($list_data)
            ->addColumn('status_aktif', function ($item) {
                if ($item->is_aktif == 1) {
                    return "Aktif";
                } else {
                    return "Non-Aktif";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_ruangan
                );
                return $data;
            })
            ->editColumn('nm_jenis_ruangan', function ($item) {
                if (isset($item->nm_kelas)) {
                    return $item->nm_jenis_ruangan . '<br> (' . $item->nm_kelas . ')';
                } else {
                    return $item->nm_jenis_ruangan;
                }
            })->rawColumns(['nm_jenis_ruangan'])
            ->make(true);
    }

    // Action POST
    public function actionRuangan(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jenis_ruangan'  => 'required',
            'id_gedung'         => 'required',
            'id_pemilik_sarpras' => 'required',
            'nm_ruangan'        => 'required',
            'kapasitas_ruangan' => 'required',
            'kapasitas_ujian'   => 'required',
            'deskripsi_ruangan' => 'required',
            'is_aktif'          => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $ruangan                        = new Ruangan;
                $ruangan->id_ruangan            = $id;
                $ruangan->id_jenis_ruangan      = $input->id_jenis_ruangan;
                $ruangan->id_kelas              = $input->id_kelas;
                $ruangan->id_gedung             = $input->id_gedung;
                $ruangan->id_pemilik_sarpras    = $input->id_pemilik_sarpras;
                $ruangan->nm_ruangan            = $input->nm_ruangan;
                $ruangan->kapasitas_ruangan     = $input->kapasitas_ruangan;
                $ruangan->kapasitas_ujian       = $input->kapasitas_ujian;
                $ruangan->deskripsi_ruangan     = $input->deskripsi_ruangan;
                $ruangan->is_aktif              = $input->is_aktif;
                $ruangan->created_by            = $input->auth_data->pengguna->id_pengguna;
                $ruangan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-ruangan/ruangan',
                    'message' => 'Save Ruangan successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $ruangan                        = Ruangan::find($id);
                $ruangan->id_jenis_ruangan      = $input->id_jenis_ruangan;
                $ruangan->id_gedung             = $input->id_gedung;
                $ruangan->id_kelas              = $input->id_kelas;
                $ruangan->id_pemilik_sarpras    = $input->id_pemilik_sarpras;
                $ruangan->nm_ruangan            = $input->nm_ruangan;
                $ruangan->kapasitas_ruangan     = $input->kapasitas_ruangan;
                $ruangan->kapasitas_ujian       = $input->kapasitas_ujian;
                $ruangan->deskripsi_ruangan     = $input->deskripsi_ruangan;
                $ruangan->is_aktif              = $input->is_aktif;
                $ruangan->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $ruangan->updated_at            = $now;
                $ruangan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-ruangan/ruangan',
                    'message' => 'Update Ruangan successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($ruanganKelas = RuanganKelas::where('id_ruangan', $id)->first() or $inventarisRuangan = InventarisRuangan::where('id_ruangan', $id)->first() or $jadwal_kelas_mp = JadwalKelasMp::where('id_ruangan', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Ruangan'
                    ];
                } else {
                    // make object to find id
                    $ruangan               = Ruangan::find($id);
                    $ruangan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $ruangan->save();

                    $ruangan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Ruangan successfully'
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

        return view('sarana-prasarana/data-sarpras-ruangan/ruangan/import-excel', compact('auth_data'));
    }

    public function importExcelAction(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'file-excel' => 'required',
        ]);

        // if ($validator->fails() && $mode != 'delete') {
        if ($validator->fails()) {
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

                        foreach ($data as $key =>  $value) {

                            $value = (object) $value;

                            if (empty($value->jenis_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada jenis ruangan yang kosong'
                                ];
                            }

                            DB::commit();

                            return [
                                'status' => 202, // SUCCESS AND LOAD CONTENT
                                'path' => 'data-sarpras-ruangan/ruangan',
                                'message' => 'Import Ruangan Successfully'
                            ];
                        }
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
                        'message'   => "File Excel anda kosong"
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
