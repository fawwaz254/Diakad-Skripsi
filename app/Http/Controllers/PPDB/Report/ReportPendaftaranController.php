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

        return view('ppdb/report/registrasi/view-report-pendaftaran',compact('auth_data'));
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

        return view('ppdb/report/registrasi/rekap-report-pendaftaran',compact('auth_data','data_penerimaan'));
    }

    public function datatablesRekapReportPendaftaran(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        //$list_data = LibPenerimaan::fetchDataReportPendaftaran($auth_data);   

        return Datatables::of($list_data)
                
                ->make(true);
    }

    public function detailReportPendaftaran($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        return view('ppdb/report/registrasi/detail-report-pendaftaran',compact('auth_data','data_penerimaan'));
    }

    public function datatablesDetailReportPendaftaran(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        //$list_data = LibPenerimaan::fetchDataReportPendaftaran($auth_data);   

        return Datatables::of($list_data)
                
                ->make(true);
    }

}
