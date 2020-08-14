<?php

namespace App\Http\Controllers\Akademik\KelasDaring;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\PengampuMapel;

use Carbon\Carbon;

use Auth;
use Session;
use Validator;

class SettingPengampuController extends BaseController
{
    public function viewSettingPengampu(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/kelas-daring/setting-pengampu/view-setting-pengampu', compact('auth_data'));
    }

    public function datatablesSettingPengampu(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = Guru::with('pengguna', 'pengampu_mapel');

        return Datatables::of($list_data)
            ->editColumn('pengguna.nm_pengguna', function ($item) {
                return $item->pengguna->fullname();
            })
            ->addColumn('jumlah_mapel', function ($item) {
                return $item->pengampu_mapel->count();
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_guru
                );
                return $data;
            })
            ->make(true);
    }

    public function viewGuruSettingPengampu(Request $request, $id_guru = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $item = Guru::find($id_guru);

        return view('akademik/kelas-daring/setting-pengampu/view-guru-setting-pengampu', compact('auth_data', 'item'));
    }

    public function datatablesGuruSettingPengampu(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $id_guru = $input->id;

        if(!empty($input->status) && $input->status == 1){
            $list_data = MataPelajaran::select('mata_pelajaran.id_mata_pelajaran', 
                                                'mata_pelajaran.kd_mata_pelajaran',
                                                'mata_pelajaran.nm_mata_pelajaran',
                                                'mata_pelajaran.tingkat_semester',
                                                'mata_pelajaran.id_jenis_mata_pelajaran',
                                                'mata_pelajaran.id_jurusan'
                                                )
                                        ->with('jenis_mata_pelajaran', 'jurusan')
                                        ->join('pengampu_mapel', function($q){
                                            $q->on('pengampu_mapel.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
                                                ->whereNull('pengampu_mapel.deleted_at');
                                        })->where('id_guru', $id_guru);
        }else{
            $list_data = MataPelajaran::select('mata_pelajaran.id_mata_pelajaran', 
                                        'mata_pelajaran.kd_mata_pelajaran',
                                        'mata_pelajaran.nm_mata_pelajaran',
                                        'mata_pelajaran.tingkat_semester',
                                        'mata_pelajaran.id_jenis_mata_pelajaran',
                                        'mata_pelajaran.id_jurusan'
                                        )->with('jenis_mata_pelajaran', 'jurusan')
                                        ->leftJoin('pengampu_mapel', function($q) use ($id_guru){
                                            $q->on('pengampu_mapel.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
                                                ->where('id_guru', $id_guru)
                                                ->whereNull('pengampu_mapel.deleted_at');
                                        })->whereNull('pengampu_mapel.id_pengampu_mapel');
        }

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_mata_pelajaran
                );
                return $data;
            })
            ->make(true);
    }

    public function actionSettingPengampu(Request $request, $mode)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_mata_pelajaran' => 'required',
            'id'                => 'required',
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            if($mode == 'set'){
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $auth_data = $input->auth_data;
        
                $pengampu_mapel                     = new PengampuMapel;
                $pengampu_mapel->id_pengampu_mapel  = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pengampu_mapel->id_mata_pelajaran  = $input->id_mata_pelajaran;
                $pengampu_mapel->id_guru            = $input->id;
                $pengampu_mapel->created_by         = $auth_data->pengguna->id_pengguna;
                $pengampu_mapel->save();
        
                return [
                    'status' => 200, // SUCCESS
                    'message' => 'Save Pengampu Mapel'
                ];
            }else{
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $auth_data = $input->auth_data;
        
                $pengampu_mapel = PengampuMapel::where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('id_guru', $input->id)->first();
                $pengampu_mapel->forceDelete();
        
                return [
                    'status' => 200, // SUCCESS
                    'message' => 'Delete Pengampu Mapel'
                ];
            }
        }
    }
}
