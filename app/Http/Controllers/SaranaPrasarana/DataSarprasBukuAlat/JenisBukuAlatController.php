<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasBukuAlat;

use App\Imports\DataImportExcel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JenisBukuAlat as JenisBukuAlat;
use App\Models\BukuAlat as BukuAlat;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;
use Excel;

class JenisBukuAlatController extends BaseController
{

    public function viewJenisBukuAlat(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/data-sarpras-buku-alat/jenis-buku-alat/view-jenis-buku-alat', compact('auth_data'));
    }

    public function addJenisBukuAlat(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jenis_buku_alat = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/data-sarpras-buku-alat/jenis-buku-alat/add-jenis-buku-alat', compact('auth_data', 'id_jenis_buku_alat'));
    }

    public function editJenisBukuAlat($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_buku_alat = LibDataSarpras::fetchDataJenisBukuAlat($auth_data, $id);

        return view('sarana-prasarana/data-sarpras-buku-alat/jenis-buku-alat/edit-jenis-buku-alat', compact('auth_data', 'data_jenis_buku_alat'));
    }

    public function datatablesJenisBukuAlat(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataJenisBukuAlat($auth_data);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jenis_buku_alat
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionJenisBukuAlat(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'kode_jenis_buku_alat' => 'required',
            'nm_jenis_buku_alat' => 'required'
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

                $jenisBukuAlat                        = new JenisBukuAlat;
                $jenisBukuAlat->id_jenis_buku_alat    = $id;
                $jenisBukuAlat->kode_jenis_buku_alat  = $input->kode_jenis_buku_alat;
                $jenisBukuAlat->nm_jenis_buku_alat    = $input->nm_jenis_buku_alat;
                $jenisBukuAlat->id_sekolah            = $input->auth_data->pengguna->id_sekolah;
                $jenisBukuAlat->created_by            = $input->auth_data->pengguna->id_pengguna;
                $jenisBukuAlat->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-buku-alat/jenis-buku-alat',
                    'message' => 'Save Jenis Buku/Alat successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $jenisBukuAlat                        = JenisBukuAlat::find($id);
                $jenisBukuAlat->kode_jenis_buku_alat  = $input->kode_jenis_buku_alat;
                $jenisBukuAlat->nm_jenis_buku_alat    = $input->nm_jenis_buku_alat;
                $jenisBukuAlat->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $jenisBukuAlat->updated_at            = $now;
                $jenisBukuAlat->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-buku-alat/jenis-buku-alat',
                    'message' => 'Update Jenis Buku/Alat successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($bukuAlat = BukuAlat::where('id_jenis_buku_alat', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jenis Buku/Alat'
                    ];
                } else {
                    // make object to find id
                    $jenisBukuAlat               = JenisBukuAlat::find($id);
                    $jenisBukuAlat->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jenisBukuAlat->save();

                    $jenisBukuAlat->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jenis Buku/Alat successfully'
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

        return view('sarana-prasarana/data-sarpras-buku-alat/jenis-buku-alat/import-excel', compact('auth_data'));
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

                        foreach ($data as $key => $value) {
                            $value = (object) $value;

                            if (empty($value->kode_jenis_buku_atau_alat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data jenis buku/alat gagal, ada kode jenis buku / alat yang kosong'
                                ];
                            }

                            if (empty($value->nama_jenis_buku_atau_alat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data jenis buku/alat gagal, ada nama jenis buku / alat yang kosong'
                                ];
                            }

                            $data                                 = new JenisBukuAlat;
                            $data->id_jenis_buku_alat             = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $data->kode_jenis_buku_alat           = $value->kode_jenis_buku_atau_alat;
                            $data->nm_jenis_buku_alat             = $value->nama_jenis_buku_atau_alat;
                            $data->id_sekolah                     = $input->auth_data->pengguna->id_sekolah;
                            $data->created_by                     = $input->auth_data->pengguna->id_pengguna;
                            $data->save();
                        }

                        DB::commit();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'data-sarpras-buku-alat/jenis-buku-alat',
                            'message' => 'Import Pemilik Sarpras Successfully'
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
