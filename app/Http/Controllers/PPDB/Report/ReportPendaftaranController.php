<?php

namespace App\Http\Controllers\PPDB\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;

use Yajra\Datatables\Datatables;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

/** 
 * Report Pendaftaran PPDB Controller
 * @author irianto
 */
class ReportPendaftaranController extends Controller
{
    
    /** 
     * View data informasi
     * @param String id_penerimaan
     * @return View
     */
    public function viewReportPendaftaran(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $mode = 'view';

        return view('ppdb/report/registrasi/view-report-pendaftaran',compact('auth_data', 'mode'));
    }

    public function datatablesReportPendaftaran(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibPenerimaan::fetchDataReportPendaftaran($auth_data);   

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_penerimaan
                    );
                    return $data;
                })
                ->make(true);
    }

    public function rekapReportPendaftaran($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        $data_rekap = LibPenerimaan::fetchDataRekapPendaftaran($auth_data, $id);

        $mode = 'rekap'; 

        return view('ppdb/report/registrasi/view-report-pendaftaran',compact('auth_data','data_penerimaan', 'data_rekap', 'mode'));
    }

    public function detailReportPendaftaran($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        $data_jurusan = LibPenerimaan::fetchDataJurusanDetailPendaftaran($auth_data, $id);

        $mode = 'detail';

        return view('ppdb/report/registrasi/view-report-pendaftaran',compact('auth_data','data_penerimaan', 'data_jurusan', 'mode'));
    }

    public function datatablesDetailReportPendaftaran(Request $request, $id_penerimaan, $id_jurusan) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibPenerimaan::fetchDataDetailPendaftaran($auth_data, $id_penerimaan, $id_jurusan);   

        return Datatables::of($list_data)

                ->make(true);
    }

}
