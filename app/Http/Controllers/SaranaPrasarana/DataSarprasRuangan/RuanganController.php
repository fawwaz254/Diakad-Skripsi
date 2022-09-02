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

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_ruangan = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/data-sarpras-ruangan/ruangan/add-ruangan', compact('auth_data', 'data_jenis_ruangan', 'data_gedung', 'data_pemilik_sarpras', 'id_ruangan'));
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

        return view('sarana-prasarana/data-sarpras-ruangan/ruangan/edit-ruangan', compact('auth_data', 'data_jenis_ruangan', 'data_gedung', 'data_pemilik_sarpras', 'data_ruangan'));
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

                        foreach ($data as $key =>  $value) {

                            $value = (object) $value;

                            if (empty($value->jenis_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada jenis ruangan yang kosong'
                                ];
                            }

                            $check_jenis_ruangan = JenisRuangan::where('nm_jenis_ruangan', ucwords($value->jenis_ruangan))->first();

                            if (!$check_jenis_ruangan) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada jenis ruangan yang tidak ditemukan dalam data master jenis ruangan'
                                ];
                            }

                            if (empty($value->nama_gedung)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada nama gedung yang kosong'
                                ];
                            }

                            $check_nama_gedung = Gedung::where('nm_gedung', ucwords($value->nama_gedung))->first();

                            if (!$check_nama_gedung) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada nama gedung yang tidak ditemukan dalam data master gedung'
                                ];
                            }

                            if (empty($value->nama_pemilik_sarpras)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada nama pemilik sarpras yang kosong'
                                ];
                            }

                            $check_nama_pemilik_sarpras = PemilikSarpras::where('nm_pemilik_sarpras', ucwords($value->nama_pemilik_sarpras))->first();

                            if (!$check_nama_pemilik_sarpras) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada nama pemilik sarpras yang tidak ditemukan dalam data master nama pemilik sarpras'
                                ];
                            }

                            if (empty($value->nama_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada nama ruangan yang kosong'
                                ];
                            }

                            if (empty($value->kapasitas_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada kapasitas ruangan yang kosong'
                                ];
                            }

                            if (empty($value->kapasitas_ujian)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada kapasitas ujian yang kosong'
                                ];
                            }

                            if (empty($value->deskripsi_ruangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada deskripsi ruangan yang kosong'
                                ];
                            }

                            if (empty($value->status_aktif)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, ada status aktif yang kosong'
                                ];
                            }

                            if (!(ucwords($value->status_aktif) == 'Aktif' || ucwords($value->status_aktif) == 'Non-Aktif')) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data ruangan gagal, status aktif yang diizinkan hanya Aktif dan Non-Aktif'
                                ];
                            }

                            // $ruangan = ucwords($value->status_aktif);

                            // if($ruangan!='Aktif'){
                            //     $is_aktif = 1;
                            // }
                            // else{
                            //     $is_aktif = 0;
                            // }

                            if (ucwords($value->status_aktif) == 'Aktif') $is_aktif = 1;
                            else $is_aktif = 0;

                            $data                                = new Ruangan;
                            $data->id_ruangan                    = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $data->id_jenis_ruangan              = $check_jenis_ruangan->id_jenis_ruangan;
                            $data->id_pemilik_sarpras            = $check_nama_pemilik_sarpras->id_pemilik_sarpras;
                            $data->id_gedung                     = $check_nama_gedung->id_gedung;
                            $data->nm_ruangan                    = $value->nama_ruangan;
                            $data->kapasitas_ruangan             = $value->kapasitas_ruangan;
                            $data->kapasitas_ujian               = $value->kapasitas_ujian;
                            $data->deskripsi_ruangan             = $value->deskripsi_ruangan;
                            $data->is_aktif                      = $is_aktif;
                            $data->created_by                    = $input->auth_data->pengguna->id_pengguna;
                            $data->save();
                        }

                        DB::commit();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'data-sarpras-ruangan/ruangan',
                            'message' => 'Import Ruangan Successfully'
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
