<?php

namespace App\Http\Controllers\Akademik\DataAkademik;

use App\Imports\DataImportExcel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\MataPelajaran as MataPelajaran;
use App\Models\JenisMataPelajaran as JenisMataPelajaran;
use App\Models\KurikulumMp as KurikulumMp;
use App\Models\KelasMp as KelasMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;
use App\Models\MapelRPP;
use App\Models\MapelRPPDetail;
use App\Models\Semester;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Validator;

class MapelRppController extends BaseController
{

    public function viewList(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $mata_pelajaran = MataPelajaran::find($request->id);

        return view('akademik/data-akademik/mata-pelajaran/view-mapel-rpp', compact('auth_data', 'mata_pelajaran'));
    }

    public function viewAdd(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $mata_pelajaran = MataPelajaran::find($request->id);
        $data_semester = Semester::orderBy('kode_semester')->get();

        return view('akademik/data-akademik/mata-pelajaran/add-mapel-rpp', compact('auth_data', 'data_semester', 'mata_pelajaran'));
    }

    public function actionDatatables(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = MapelRPP::with('mata_pelajaran', 'semester')->where('id_mata_pelajaran', $request->id);

        return Datatables::of($list_data)
            ->editColumn('semester.kode_semester', function ($item) {
                return $item->semester->semesterLengkap();
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_mapel_rpp
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionItem(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        if($mode == 'delete'){
            MapelRPP::where('id_mapel_rpp', $id)->delete();
            MapelRPPDetail::where('id_mapel_rpp', $id)->delete();

            return [
                'status'    => 203, // SUCCESS
                'message'   => 'Delete Data Successfully'
            ];
        }

        if ($request->hasFile('file')) {
            $data_excel = Excel::toArray(new DataImportExcel, $request->file('file'));
            $worksheet1 = $data_excel[0];
            $role = $input->role;

            if (count($worksheet1)) {
                DB::beginTransaction();
                try {
                    $mata_pelajaran = MataPelajaran::find($request->id_mata_pelajaran);
                    if($mapel_rpp = MapelRPP::where('id_mata_pelajaran', $request->id_mata_pelajaran)->where('id_semester', $request->id_semester)->first()){
                        $mapel_rpp->updated_by = $auth_data->pengguna->id_pengguna;
                    }else{
                        $mapel_rpp = new MapelRPP();
                        $mapel_rpp->id_mata_pelajaran = $request->id_mata_pelajaran;
                        $mapel_rpp->id_semester = $request->id_semester;
                        $mapel_rpp->created_by = $auth_data->pengguna->id_pengguna;
                    }
                    $mapel_rpp->save();

                    MapelRPPDetail::where('id_mapel_rpp', $mapel_rpp->id_mapel_rpp)->delete();

                    foreach ($worksheet1 as $key => $row_excel) {
                        $row_excel = (object) $row_excel;

                        if(!empty($row_excel->pertemuan_ke)){
                            $mapel_rpp_detail                       = new MapelRPPDetail();
                            $mapel_rpp_detail->id_mapel_rpp         = $mapel_rpp->id_mapel_rpp;
                            $mapel_rpp_detail->pertemuan_ke         = $row_excel->pertemuan_ke;
                            $mapel_rpp_detail->deskripsi            = $row_excel->deskripsi;
                            $mapel_rpp_detail->model_pembelajaran   = $row_excel->model_pembelajaran;

                            $nilai_karater = '';
                            foreach(explode(',', $row_excel->karakter_yang_ditanamkan) as $kk){
                                if (in_array(strtolower(rtrim(ltrim($kk))), [
                                    'disiplin',
                                    'tangguh dan tanggung jawab',
                                    'peduli',
                                    'komunikasi',
                                    'kolaboratif',
                                    'berpikir kritis',
                                    'kejujuran',
                                    'religius',
                                    'kreatif'
                                ])) {
                                    
                                }else{
                                    return [
                                        'status'    => 203, // GAGAL
                                        'message'   => 'Terdapat karakter di luar Disiplin, Tangguh dan Tanggung Jawab, Peduli, Komunikasi, Kolaboratif, Berpikir Kritis, Kejujuran, Religius, Kreatif : '.strtolower($kk)
                                    ];
                                }
                                $nilai_karater .= rtrim(ltrim($kk)).'#';
                            }
                            $mapel_rpp_detail->nilai_karakter   = rtrim($nilai_karater, '#');
                            $mapel_rpp_detail->created_by       = auth_data()->pengguna->id_pengguna;
                            $mapel_rpp_detail->save();
                        }
                    }

                    DB::commit();
                    return [
                        'status'    => 202, // SUCCESS AND LOAD CONTENT
                        'path'      => ($role == 'akademik') ? 'data-akademik/mata-pelajaran/rpp?id=' . $mata_pelajaran->id_mata_pelajaran : 'jadwal/mata-pelajaran/rpp?id=' . $mata_pelajaran->id_mata_pelajaran,
                        'message'   => 'Upload Data Successfully'
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

    public function previewRPP(Request $request)
    {
        $input = (object) $request->input();
        $id_mapel_rpp = $input->id;
        
        $mapel_rpp = MapelRPP::where('id_mapel_rpp', $id_mapel_rpp)->get()->first();

        $detail_rpp = MapelRPPDetail::where('id_mapel_rpp', $id_mapel_rpp)->get();

        $pdf = Pdf::loadView('akademik/data-akademik/mata-pelajaran/previewRPP', compact('mapel_rpp', 'detail_rpp'));
        
        return $pdf->stream('rpp_mapel.pdf');
    }
}
