<?php

namespace App\Http\Controllers\Humas\JurnalHarian;

use Excel;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Imports\DataImportExcel;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\JenisJurnalHarianTendik;

class JenisKategoriJurnalHarianController extends Controller
{
    public function viewDataJenis(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_pengguna;

        return view('humas/jurnal-harian/data-jenis/view-data-jenis', compact('auth_data'));
    }

    public function datatablesjenis(Request $request)
    {

        $list_data = '';
        $list_data = JenisJurnalHarianTendik::all();
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jenis_jurhart
                );
                return $data;
            })->make(true);
    }

    public function addDataJenis(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/jurnal-harian/data-jenis/add-data-jenis', compact('auth_data'));
    }

    public function actionDataJenis(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'jenis_jurhart' => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            //mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $data_jenis                               = new JenisJurnalHarianTendik();
                $data_jenis->id_jenis_jurhart             = $id;
                $data_jenis->jenis_jurhart                = $input->jenis_jurhart;
                $data_jenis->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $data_jenis->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'jurnal-harian/jenis-jurnal-harian',
                    'message' => 'Save Data Jenis Succesfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id 
                $data_jenis                       = JenisJurnalHarianTendik::where('id_jenis_jurhart', $id)->first();
                $data_jenis->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                $data_jenis->save();
                $data_jenis->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Jenis succesfully'
                ];
            }
        }
    }

    public function importExcel(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/jurnal-harian/data-jenis/import-excel', compact('auth_data'));
    }

    public function importExcelAction(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'file-excel' => 'required',
        ]);

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
                        foreach ($data as $key => $value) {
                            $value = (object) $value;

                            if (empty($value->nama_jenis_jurnal_harian)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload Gagal, Kolom Nama Jenis Jurnal Harian Masih Kosong'
                                ];
                            }

                            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $data_jenis                               = new JenisJurnalHarianTendik();
                            $data_jenis->id_jenis_jurhart             = $id;
                            $data_jenis->jenis_jurhart                = $value->nama_jenis_jurnal_harian;
                            $data_jenis->created_by                   = $input->auth_data->pengguna->id_pengguna;
                            $data_jenis->save();
                        }
                        DB::commit();
                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'jurnal-harian/jenis-jurnal-harian',
                            'message' => 'Save Data Jenis Succesfully'
                        ];
                    } catch (\Exception $e) {
                        DB::rollback();
                        return [
                            'status'    => 203, // GAGAL
                            'message'   => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
                        ];
                    }
                } else {
                    return [
                        'status'    => 300, // FAILED
                        'message'   => "File Excel Kosong"
                    ];
                }
            } else {
                return [
                    'status'    => 300, // FAILED
                    'message'   => "File Excel Tidak Ditemukan"
                ];
            }
        }
    }
}
