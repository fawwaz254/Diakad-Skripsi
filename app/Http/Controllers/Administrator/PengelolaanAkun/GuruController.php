<?php

namespace App\Http\Controllers\Administrator\PengelolaanAkun;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Role as Role;
use App\Models\RolePengguna as RolePengguna;

use Auth;
use DB;
use Session;
use Validator;

class GuruController extends BaseController
{
    public function viewGuru(Request $request, $id_role = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_role = Role::select(
            DB::raw("role.id_role, nm_role, (SELECT COUNT(*) FROM role_pengguna JOIN pengguna ON pengguna.id_pengguna = role_pengguna.id_pengguna WHERE role_pengguna.id_role = role.id_role AND role_pengguna.deleted_at IS NULL AND pengguna.status_join_table = 2 AND pengguna.id_sekolah = ? ) AS total_role")
        )
                        ->join('role_pengguna AS rp', function ($join) {
                            $join->on('rp.id_role', '=', 'role.id_role')
                                             ->whereNull('rp.deleted_at');
                        })
                        ->join('pengguna AS p', 'p.id_pengguna', '=', 'rp.id_pengguna')
                        ->where('p.status_join_table', '=', "?")
                        ->where('p.id_sekolah', '=', "?")
                        ->orderBy('role.nm_role', 'asc')
                        ->distinct()
                        ->setBindings([$auth_data->pengguna->id_sekolah, 2, $auth_data->pengguna->id_sekolah])
                        ->get();

        return view('administrator/pengelolaan-akun/guru/view-guru', compact('auth_data', 'data_role', 'id_role'));
    }

    public function actionViewGuru(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
          'id_role' =>'required'
      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengelolaan-akun/guru/view-detail/'.$input->id_role
                ];
        }
    }
    public function viewDetailGuru(Request $request, $id_role)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_role = Role::select(
            DB::raw("role.id_role, nm_role, (SELECT COUNT(*) FROM role_pengguna JOIN pengguna ON pengguna.id_pengguna = role_pengguna.id_pengguna WHERE role_pengguna.id_role = role.id_role AND role_pengguna.deleted_at IS NULL AND pengguna.status_join_table = 2 AND pengguna.id_sekolah = ? ) AS total_role")
        )
                        ->join('role_pengguna AS rp', function ($join) {
                            $join->on('rp.id_role', '=', 'role.id_role')
                                             ->whereNull('rp.deleted_at');
                        })
                        ->join('pengguna AS p', 'p.id_pengguna', '=', 'rp.id_pengguna')
                        ->where('p.status_join_table', '=', "?")
                        ->where('p.id_sekolah', '=', "?")
                        ->orderBy('role.nm_role', 'asc')
                        ->distinct()
                        ->setBindings([$auth_data->pengguna->id_sekolah, 2, $auth_data->pengguna->id_sekolah])
                        ->get();
  
        return view('administrator/pengelolaan-akun/guru/view-guru', compact('auth_data', 'data_role', 'id_role'));
    }

    public function datatablesGuru(Request $request, $id_role)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rolePengguna = RolePengguna::select('pengguna.id_pengguna', 'guru.nip_guru', 'pengguna.username', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'role.nm_role', 'unit_kerja.nm_unit_kerja')
                        ->join('role', function ($q) {
                            $q->on('role.id_role', '=', 'role_pengguna.id_role')
                                ->whereNull('role.deleted_at');
                        })
                        ->join('pengguna', function ($q) {
                            $q->on('pengguna.id_pengguna', '=', 'role_pengguna.id_pengguna')
                                ->whereNull('pengguna.deleted_at');
                        })
                        ->join('guru', function ($q) {
                            $q->on('guru.id_pengguna', '=', 'pengguna.id_pengguna')
                                ->whereNull('guru.deleted_at');
                        })
                        ->join('unit_kerja', function ($q) {
                            $q->on('unit_kerja.id_unit_kerja', '=', 'guru.id_unit_kerja')
                                ->whereNull('unit_kerja.deleted_at');
                        })
                        ->where('pengguna.status_join_table', '=', 2)
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->where('role_pengguna.id_role', '=', $id_role)
                        ->orderBy('pengguna.nm_pengguna', 'asc')
                        ->get();

        return Datatables::of($rolePengguna)
                ->addColumn('nm_pengguna', function ($item) {
                    if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    } elseif (! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;
                    } elseif (! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna;
                    }
                })
                ->make(true);
    }
}
