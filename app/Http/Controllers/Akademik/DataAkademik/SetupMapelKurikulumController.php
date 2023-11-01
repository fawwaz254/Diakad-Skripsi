<?php

namespace App\Http\Controllers\Akademik\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\JenisMataPelajaran as JenisMataPelajaran;
use App\Models\Jurusan as Jurusan;
use App\Models\MataPelajaran as MataPelajaran;
use App\Models\Kurikulum as Kurikulum;
use App\Models\KurikulumMp as KurikulumMp;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class SetupMapelKurikulumController extends BaseController
{
    public function viewSetupMapelKurikulum(Request $request, $id_kurikulum = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        //get kurikulum aktif di sekolah tersebut
        $kurikulum = Kurikulum::with('jurusan')
            ->withCount('mapel')
            ->where('is_aktif', '=', 1)
            ->whereHas('jurusan', function ($q) use ($auth_data) {
                $q->where('id_sekolah', $auth_data->pengguna->id_sekolah);
            })
            ->get();

        return view('akademik/data-akademik/setup-mapel-kurikulum/view-setup-mapel-kurikulum', compact('auth_data', 'kurikulum', 'id_kurikulum'));
    }

    public function actionCariKurikulum(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kurikulum' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'data-akademik/setup-mp-kurikulum/view-mapel-kurikulum/' . $input->id_kurikulum

            ];
        }
    }

    public function viewKurikulumMataPelajaran(Request $request, $id_kurikulum)
    {
        # code...

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        //get kurikulum aktif di sekolah tersebut
        $kurikulum = Kurikulum::with('jurusan')
            ->withCount('mapel')
            ->where('is_aktif', '=', 1)
            ->whereHas('jurusan', function ($q) use ($auth_data) {
                $q->where('id_sekolah', $auth_data->pengguna->id_sekolah);
            })
            ->get();

        $jurusan = Kurikulum::join('jurusan', 'jurusan.id_jurusan', '=', 'kurikulum.id_jurusan')->first();

        return view('akademik/data-akademik/setup-mapel-kurikulum/view-setup-mapel-kurikulum', compact('auth_data', 'kurikulum', 'id_kurikulum', 'jurusan'));
    }

    public function addMapelKurikulum(Request $request, $id_kurikulum)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // $jurusan = Jurusan::where('id_jurusan','=',$id_jurusan)->first();

        return view('akademik/data-akademik/setup-mapel-kurikulum/add-mapel-kurikulum', compact('auth_data', 'id_kurikulum'));
    }

    public function datatablesSetupMapelKurikulum(Request $request, $id_kurikulum)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KurikulumMp::join('kurikulum', 'kurikulum.id_kurikulum', '=', 'kurikulum_mp.id_kurikulum')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kurikulum_mp.id_mata_pelajaran')
            ->leftJoin('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->where('kurikulum.id_kurikulum', '=', $id_kurikulum)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('jenis_mata_pelajaran', function ($item) {
                return $item->kode_jenis_mata_pelajaran . " " . $item->nm_jenis_mata_pelajaran;
            })
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->kd_mata_pelajaran . " " . $item->nm_mata_pelajaran;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kurikulum_mp
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesaddMapelKurikulum(Request $request, $id_jurusan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = MataPelajaran::leftJoin('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('kurikulum_mp')
                    ->whereRaw('kurikulum_mp.id_mata_pelajaran = mata_pelajaran.id_mata_pelajaran');
            })->where('mata_pelajaran.is_aktif', '=', '1')
            ->get();

        return Datatables::of($list_data)
            ->addColumn('jenis_mata_pelajaran', function ($item) {
                return $item->kode_jenis_mata_pelajaran . " " . $item->nm_jenis_mata_pelajaran;
            })
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->kd_mata_pelajaran . " " . $item->nm_mata_pelajaran;
            })
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id' => $item->id_mata_pelajaran
                );
                return $data;
            })
            ->make(true);
    }

    public function actionJenisMataPelajaran(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), []);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'tambah-mapel') {
                DB::beginTransaction();

                try {
                    foreach ($input->id_mata_pelajaran as $id_mata_pelajaran) {
                        $kurikulumMp                        = new KurikulumMp;
                        $kurikulumMp->id_kurikulum_mp       = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $kurikulumMp->id_kurikulum          = $id;
                        $kurikulumMp->id_mata_pelajaran     = $id_mata_pelajaran;
                        $kurikulumMp->created_by            = $input->auth_data->pengguna->id_pengguna;
                        $kurikulumMp->created_at            = $now;
                        $kurikulumMp->save();
                    }
                    DB::commit();
                    return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'message' => 'Setup Kurikulum Berhasil',
                        'path' => 'data-akademik/setup-mp-kurikulum/view-mapel-kurikulum/' . $id
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Setup Kurikulum Gagal'
                    ];
                }
            } elseif ($mode == 'delete') {
                $kurikulumMp    = KurikulumMp::find($id);
                // $kurikulumMp->deleted_by    = $input->auth_data->pengguna->id_pengguna;
                // $kurikulumMp->deleted_at    = $now;
                // $kurikulumMp->save();

                $kurikulumMp->forceDelete();
                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Mata Pelajaran Berhasil Di Hapus '
                ];
            }
        }
    }
}
