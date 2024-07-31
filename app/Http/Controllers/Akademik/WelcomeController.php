<?php

namespace App\Http\Controllers\Akademik;

use App\Models\MapelRPP;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\RoleDashboard;

use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;

use App\Models\Setting;

class WelcomeController extends BaseController
{

    public function indexWelcome(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($start_monkes = Setting::where('key_setting', 'start_monkes')->first()) {
            $start_monkes = $start_monkes->value;
        } else {
            $start_monkes = '19:00';
        }

        if ($end_monkes = Setting::where('key_setting', 'end_monkes')->first()) {
            $end_monkes = $end_monkes->value;
        } else {
            $end_monkes = '07:00';
        }
        $role_aktif = $auth_data->role_aktif;


        $totalUpload1HariTerakhir = DB::table('mapel_rpp')
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        $totalUpload1MingguTerakhir = DB::table('mapel_rpp')
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->count();

        $totalUpload1BulanTerakhir = DB::table('mapel_rpp')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->count();

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();
        return view('akademik/welcome', compact('auth_data', 'start_monkes', 'end_monkes', 'role_dashboard', 'totalUpload1HariTerakhir', 'totalUpload1MingguTerakhir', 'totalUpload1BulanTerakhir')); //folder akademik/nama file welcome.blade
    }

    public function datatableReportRpp(Request $request)
    {
        $query = Pengguna::select('pengguna.id_pengguna', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang')
            ->join('guru', function ($q) {
                $q->on('guru.id_pengguna', '=', 'pengguna.id_pengguna')
                    ->whereNull('guru.deleted_at');
            })
            ->addSelect([
                'jumlah_rpp' => MapelRPP::selectRaw('count(*)')
                    ->whereColumn('created_by', 'pengguna.id_pengguna')
            ]);

        return Datatables::of($query)
            ->addColumn('nm_pengguna', function ($item) {
                if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                    return $item->gelar_depan . " " . $item->nm_pengguna . ", " . $item->gelar_belakang;
                } elseif (!empty($item->gelar_depan)) {
                    return $item->gelar_depan . " " . $item->nm_pengguna;
                } elseif (!empty($item->gelar_belakang)) {
                    return $item->nm_pengguna . ", " . $item->gelar_belakang;
                } else {
                    return $item->nm_pengguna;
                }
            })
            ->addColumn('jumlah_rpp', function ($item) {
                return $item->jumlah_rpp; // Use the preloaded count
            })
            ->addColumn('action', function ($item) {
                return [
                    'id' => $item->id_pengguna
                ];
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function modalDetailRpp($id)
    {
        $detail_rpp = MapelRPP::select('mapel_rpp.*', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'mapel_rpp.id_mata_pelajaran')
            ->where('mapel_rpp.created_by', $id)
            ->get();

        $pengguna = Pengguna::select('nm_pengguna', 'gelar_depan', 'gelar_belakang')
            ->where('id_pengguna', $id)
            ->first();

        $nama_guru = $pengguna ? trim($pengguna->gelar_depan . ' ' . $pengguna->nm_pengguna . ' ' . $pengguna->gelar_belakang) : 'Unknown';

        return view('akademik.detail_rpp', compact('detail_rpp', 'nama_guru'));
    }

}
