<?php

namespace App\Http\Controllers\SumberDaya\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruKpi;
use App\Models\Pengguna;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use DB;
use Carbon\Carbon;
use Validator;

class SettingGuruKpiController extends BaseController
{
    public function viewSettingGuruKpi(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/guru/setting-guru-kpi/view-setting-guru-kpi', compact('auth_data'));
    }

    public function addSettingGuruKpi(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/guru/setting-guru-kpi/view-add-setting-guru-kpi', compact('auth_data'));
    }

    public function editSettingGuruKpi(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = GuruKpi::select('guru.nip_guru', 'staff.nip_staff', 'pengguna.nm_pengguna', 'guru_kpi.id_guru_kpi', 'guru_kpi.is_aktif')
        ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru_kpi.id_pengguna')
        ->leftjoin('guru', 'guru.id_pengguna', '=', 'guru_kpi.id_pengguna')
        ->leftjoin('staff', 'staff.id_pengguna', '=', 'guru_kpi.id_pengguna')
        ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
        ->where('guru_kpi.id_guru_kpi', '=', $id)->first();

        return view('sumber-daya/guru/setting-guru-kpi/view-edit-setting-guru-kpi', compact('auth_data', 'guru'));
    }

    public function datatablesSettingGuruKpi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = GuruKpi::selectRaw('ukg.nm_unit_kerja AS unit_kerja_guru, uks.nm_unit_kerja AS unit_kerja_staff')
        ->addSelect('guru.nip_guru', 'staff.nip_staff', 'pengguna.nm_pengguna', 'guru_kpi.id_guru_kpi', 'guru_kpi.is_aktif')
        ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru_kpi.id_pengguna')
        ->leftjoin('guru', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
        ->leftjoin('staff', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
        ->leftjoin('unit_kerja AS ukg', 'ukg.id_unit_kerja', '=', 'guru.id_unit_kerja')
        ->leftjoin('unit_kerja AS uks', 'uks.id_unit_kerja', '=', 'staff.id_unit_kerja')
        ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
        ->get();

        return DataTables::of($list_data)
                ->addColumn('nip_pengguna', function ($item) {
                    if (!empty($item->nip_guru)) {
                        return $item->nip_guru;
                    } else {
                        return $item->nip_staff;
                    }
                })
                ->addColumn('nm_unit_kerja', function ($item) {
                    if (!empty($item->unit_kerja_guru)) {
                        return $item->unit_kerja_guru;
                    } else {
                        return $item->unit_kerja_staff;
                    }
                })
                ->addColumn('is_aktif', function ($item) {
                    if ($item->is_aktif == 1) {
                        return "Aktif";
                    } else {
                        return "Non-Aktif";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_guru_kpi
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesAddGuruKpi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = Pengguna::selectRaw('ukg.nm_unit_kerja AS unit_kerja_guru, uks.nm_unit_kerja AS unit_kerja_staff')
        ->addSelect('pengguna.nm_pengguna', 'guru.nip_guru', 'staff.nip_staff', 'pengguna.id_pengguna')
        ->leftjoin('guru', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
        ->leftjoin('staff', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
        ->leftjoin('unit_kerja AS ukg', 'ukg.id_unit_kerja', '=', 'guru.id_unit_kerja')
        ->leftjoin('unit_kerja AS uks', 'uks.id_unit_kerja', '=', 'staff.id_unit_kerja')
        ->whereIn('pengguna.status_join_table', [1, 2])
        ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
           ->whereNotExists(function ($query) {
               $query->select(DB::raw(1))
                      ->from('guru_kpi')
                      ->whereRaw('guru_kpi.id_pengguna = pengguna.id_pengguna');
           })
        ->get();

        return Datatables::of($list_data)
                ->addColumn('nip_pengguna', function ($item) {
                    if (!empty($item->nip_guru)) {
                        return $item->nip_guru;
                    } else {
                        return $item->nip_staff;
                    }
                })
                ->addColumn('nm_unit_kerja', function ($item) {
                    if (!empty($item->unit_kerja_guru)) {
                        return $item->unit_kerja_guru;
                    } else {
                        return $item->unit_kerja_staff;
                    }
                })
                ->addColumn('is_aktif', function ($item) {
                    if ($item->is_aktif == "1") {
                        return "Aktif";
                    } else {
                        return "Tidak aktif";
                    }
                })
                ->addColumn('checkbox', function ($item) {
                    $data = array(
                        'id' => $item->id_pengguna
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionSettingGuruKpi(Request $request, $mode, $id = null)
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
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    foreach ($input->id_pengguna as $id_pengguna) {
                        $guru                        	= new GuruKpi;
                        $guru->id_guru_kpi  	        = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $guru->id_pengguna 	            = $id_pengguna;
                        $guru->created_by            	= $input->auth_data->pengguna->id_pengguna;
                        $guru->created_at            	= $now;
                        $guru->is_aktif 				= 1;
                        $guru->save();
                    }
                    DB::commit();
                    return [
                            'status' => 204, // SUCCESS AND LOAD CONTENT
                            'message' => 'Tambah Guru KPI Berhasil',
                            'path' => 'guru/setting-guru-kpi/'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
                                'message' => 'Tambah Guru KPI Gagal'
                            ];
                }
            } elseif ($mode == "edit") {
                $guru 		= GuruKpi::find($id);
                $guru->is_aktif	= $input->is_aktif;
                $guru->updated_at	= $now;
                $guru->updated_by	= $input->auth_data->pengguna->id_pengguna;
                $guru->save();

                return [
                            'status' => 204, // SUCCESS AND LOAD CONTENT
                            'message' => 'Edit Guru KPI Berhasil',
                            'path' => 'guru/setting-guru-kpi/'
                    ];
            } elseif ($mode == "delete") {
                $guru 	= GuruKpi::find($id);
                $guru->forceDelete();
                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Guru KPI Berhasil Dihapus'
                ];
            }
        }
    }
}
