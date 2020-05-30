<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\DetailBiaya;
use App\Models\Semester;
use App\Models\Siswa;

use Auth;
use DB;
use Session;
use Validator;

class SppController extends BaseController
{
    public function viewMenuSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        return view('keuangan/sim/spp/view-menu-spp', compact('auth_data'));
    }

    public function viewMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();
        
        return view('keuangan/sim/spp/view-menu-cari', compact('auth_data', 'data_semester'));
    }

    public function datatablesMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        if (!empty($input->start_semester) && !empty($input->end_semester)) {
            $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function($q) use ($input){
                $q->whereIn('id_semester', [$input->start_semester, $input->end_semester]);
            })->whereNotNull('id_bulan')->get();

            $list_data = Siswa::with(['tagihan_biaya' => function($q) use ($data_detail_biaya){
                $q->where('is_tagih', 1)
                    ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
            }])
            ->with('pengguna', 'kelas');
        }else{
            $list_data = array();
        }

        return Datatables::of($list_data)
                ->addColumn('total_tagihan_bulan', function ($item) {
                    return 'Rp'.number_format($item->tagihan_biaya->sum('besar_biaya'));
                })
                ->addColumn('tagihan_bulan', function ($item) use ($data_detail_biaya) {
                    $array_tagihan_bulan = array();
                    foreach($item->tagihan_biaya as $tagihan){
                        $tagihan_bulan['id_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->id_bulan;
                        $tagihan_bulan['nm_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->nm_bulan;

                        $array_tagihan_bulan[] = $tagihan_bulan;
                    }

                    $array_tagihan_bulan = collect($array_tagihan_bulan)->sortBy('id_bulan')->pluck('nm_bulan');

                    return $array_tagihan_bulan;
                })
                ->make(true);
    }
}
