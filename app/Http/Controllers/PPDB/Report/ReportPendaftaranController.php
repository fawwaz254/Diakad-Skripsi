<?php

namespace App\Http\Controllers\PPDB\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;

use Yajra\Datatables\Datatables;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;
use App\Models\CalonSiswaBaru;

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

        return view('ppdb/report/registrasi/view-report-pendaftaran', compact('auth_data', 'mode'));
    }

    public function datatablesReportPendaftaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibPenerimaan::fetchDataReportPendaftaran($auth_data);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_penerimaan
                );
                return $data;
            })
            ->make(true);
    }

    public function rekapReportPendaftaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        $data_rekap = LibPenerimaan::fetchDataRekapPendaftaran($auth_data, $id);

        $mode = 'rekap';

        return view('ppdb/report/registrasi/view-report-pendaftaran', compact('auth_data', 'data_penerimaan', 'data_rekap', 'mode'));
    }

    public function detailReportPendaftaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        $data_jurusan = LibPenerimaan::fetchDataJurusanDetailPendaftaran($auth_data, $id);

        $mode = 'detail';

        return view('ppdb/report/registrasi/view-report-pendaftaran', compact('auth_data', 'data_penerimaan', 'data_jurusan', 'mode'));
    }

    public function datatablesDetailReportPendaftaran(Request $request, $id_penerimaan, $id_jurusan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($id_jurusan != '0') {
            $list_data = LibPenerimaan::fetchDataDetailPendaftaran($auth_data, $id_penerimaan, $id_jurusan);
        } else {
            $list_data = CalonSiswaBaru::select('calon_siswa_baru.id_c_siswa',  'calon_siswa_baru.nomor_ujian', 'calon_siswa_baru.kode_voucher', 'calon_siswa_baru.nm_c_siswa', 'calon_siswa_baru.nomor_hp', 'calon_siswa_sekolah.nm_sekolah_asal', 'calon_siswa_ortu.nm_ayah', 'calon_siswa_ortu.nm_ibu', 'calon_siswa_ortu.nomor_telp_ortu', 'calon_siswa_ortu.nomor_hp_ortu')
                ->join('calon_siswa_sekolah', function ($q) {
                    $q->on('calon_siswa_sekolah.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                        ->whereNull('calon_siswa_sekolah.deleted_at');
                })
                ->join('calon_siswa_ortu', function ($q) {
                    $q->on('calon_siswa_ortu.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                        ->whereNull('calon_siswa_ortu.deleted_at');
                })
                ->where('calon_siswa_baru.id_penerimaan', '=', $id_penerimaan)
                ->whereNotNull('calon_siswa_baru.tgl_submit_form')
                ->orderBy('calon_siswa_baru.nm_c_siswa', 'asc');
        }


        return Datatables::of($list_data)

            ->make(true);
    }
}
