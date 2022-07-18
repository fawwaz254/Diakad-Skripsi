<?php

namespace App\Http\Controllers\PPDB\Peserta;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\CalonSiswaBaru as CalonSiswaBaru;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

use Illuminate\Support\Facades\Hash;

use Auth;
use DB;
use Session;
use Validator;

class ProsesPenetapanController extends BaseController
{
    /**
     * View page awal proses penetapan, show list data penerimaan
     * @param Request
     * @return View
     */
    public function viewProsesPenetapan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        $mode = 'view';

        return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun', 'mode'));
    }

    /**
     * Action post view for editing proses penetapan
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewProsesPenetapan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator  = Validator::make($request->all(), [
            'id_penerimaan' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            return [
                'status'    => 204, // SUCCESS AND LOAD CONTENT
                'path'      => 'peserta/proses-penetapan/'.$input->id_penerimaan
            ];
        }
    }

    /**
     * View detail proses penetapan, to show list calon siswa at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function showPeserta($id, Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $data_penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) {
            abort(404);
        }

        $mode = 'show';

        return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'grup_penerimaan_tahun', 'penerimaan', 'mode'));
    }

    public function datatablesProsesPenetapan($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = LibPenerimaan::fetchDataCalonSiswaPenetapan($auth_data, $id);

        return Datatables::of($list_data)
                ->addColumn('checkbox', function ($item) {
                    $data = array(
                        'id' => $item->id_c_siswa
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionPenetapan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            DB::beginTransaction();

            try {
                foreach ($input->id_c_siswa as $id_c_siswa) {
                    $c_siswa                = CalonSiswaBaru::find($id_c_siswa);
                    $c_siswa->nomor_ujian   = 'U-'.$c_siswa->kode_voucher;
                    $c_siswa->updated_at    = $now;
                    $c_siswa->updated_by    = $input->auth_data->pengguna->id_pengguna;
                    $c_siswa->save();
                }
                DB::commit();
                return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'message' => 'Penetapan Berhasil',
                        'path' => 'peserta/proses-penetapan/'.$input->id_penerimaan
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong

                return [
                            'status' => 203, // GAGAL
                            'message' => 'Proses Penetapan Gagal'
                        ];
            }
        }
    }
}
