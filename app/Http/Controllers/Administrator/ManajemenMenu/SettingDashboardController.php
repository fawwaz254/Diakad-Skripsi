<?php

namespace App\Http\Controllers\Administrator\ManajemenMenu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Role as Role;
use App\Models\RoleDashboard as RoleDashboard;

use Auth;
use DB;
use Session;
use Validator;

class SettingDashboardController extends BaseController
{
     public function viewSettingDashboard(Request $request, $id_role = null){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

      $data_role = Role::select('id_role', 'nm_role')
                        ->orderBy('role.nm_role', 'asc')
                        ->get();

      if ($id_role != null) {
        $role_dashboard = RoleDashboard::select('role.nm_role','role_dashboard.isi_dashboard')
                            ->join('role','role.id_role','=','role_dashboard.id_role')
                            ->where('role_dashboard.id_role', '=' , $id_role)
                            ->first();

        if( ! empty($role_dashboard)) {
          $isi_dashboard  = $role_dashboard->isi_dashboard;
          $nm_role        = $role_dashboard->nm_role;
        }
        else {
          $isi_dashboard  = null;
          $nm_role        = null;
        }
      }
      else {
        $isi_dashboard  = null;
        $nm_role        = null;
      }

    	return view('administrator/manajemen-menu/setting-dashboard/view-setting-dashboard',compact('auth_data','data_role','id_role', 'isi_dashboard', 'nm_role'));
  	}

  	public function actionViewSettingDashboard(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $validator = Validator::make($request->all(), [
          'id_role' =>'required'
      ]);

      if($validator->fails()) {
          return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
      }
      else {
          return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-menu/setting-dashboard/view-detail/'.$input->id_role
                ];
      }
  }
  public function viewDetailSettingDashboard(Request $request, $id_role){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_role = Role::select('id_role', 'nm_role')
                        ->orderBy('role.nm_role', 'asc')
                        ->get();

        if ($id_role != null) {
          $role_dashboard = RoleDashboard::select('role.nm_role','role_dashboard.isi_dashboard')
                              ->join('role','role.id_role','=','role_dashboard.id_role')
                              ->where('role_dashboard.id_role', '=' , $id_role)
                              ->first();

          $role = Role::select('id_role', 'nm_role')
                        ->where('id_role', '=' , $id_role)
                        ->first();

          if( ! empty($role_dashboard)) {
            $isi_dashboard  = $role_dashboard->isi_dashboard;
            $nm_role        = $role->nm_role;
          }
          else {
            $isi_dashboard  = null;
            $nm_role        = $role->nm_role;
          }
        }
        else {
          $isi_dashboard  = null;
          $nm_role        = null;
        }

        return view('administrator/manajemen-menu/setting-dashboard/view-detail-setting-dashboard',compact('auth_data','data_role','id_role', 'isi_dashboard', 'nm_role'));

    }

    /** 
     * Action post data informasi
     * @param String isi_dashboard
     * @return Code 300 fail, 202 success
     */
    public function actionSettingDashboard(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_role'       => 'required',
            // 'isi_dashboard' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            /** find data dashboard */
            $data_dashboard = RoleDashboard::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                ->where('id_role','=',$input->id_role)
                                ->first();

            if($data_dashboard == null){ // CREATE INFORMASI DATA
                /** generate id_data_informasi */
                $id_role_dashboard = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                /** create data dashboard */
                $data_dashboard                     = new RoleDashboard;
                $data_dashboard->id_role_dashboard  = $id_role_dashboard;
                $data_dashboard->id_role            = $input->id_role;
                $data_dashboard->isi_dashboard      = $input->isi_dashboard;
                $data_dashboard->is_aktif           = 1;
                $data_dashboard->id_sekolah         = $input->auth_data->sekolah_data->id_sekolah;
                $data_dashboard->created_by         = $input->auth_data->pengguna->id_pengguna;
                $data_dashboard->save();
            } else { // UPDATE
                /** update data dashboard */ 
                $data_dashboard->isi_dashboard      = $input->isi_dashboard;
                $data_dashboard->is_aktif           = 1;
                $data_dashboard->updated_at         = $now;
                $data_dashboard->updated_by         = $input->auth_data->pengguna->id_pengguna;
                $data_dashboard->save();
            }

            return [
                'status'    => 202, // SUCCESS AND LOAD CONTENT
                'path'      => 'manajemen-menu/setting-dashboard/view-detail/'.$input->id_role,
                'message'   => 'Data Dashboard Saved Successfully'
            ];
        }
    }

    
}
