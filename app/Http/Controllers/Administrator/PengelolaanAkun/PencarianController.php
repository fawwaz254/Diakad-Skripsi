<?php

namespace App\Http\Controllers\Administrator\PengelolaanAkun;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\Staff as Staff;
use App\Models\Guru as Guru;
use App\Models\Role as Role;
use App\Models\RolePengguna as RolePengguna;

use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class PencarianController extends BaseController
{
     public function viewPencarian(Request $request, $username_nama_cari = null){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

    	return view('administrator/pengelolaan-akun/pencarian/view-pencarian',compact('auth_data','username_nama_cari'));
  	}

  	public function actionViewPencarian(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $validator = Validator::make($request->all(), [
          'username_nama_cari' =>'required'
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
                    'path' => 'pengelolaan-akun/pencarian/view-detail/'.$input->username_nama_cari
                ];
      }
  }
  public function viewDetailPencarian(Request $request, $username_nama_cari){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
  
        return view('administrator/pengelolaan-akun/pencarian/view-pencarian',compact('auth_data','username_nama_cari'));
    }

    public function datatablesPencarian(Request $request, $username_nama_cari){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pengguna = Pengguna::select('pengguna.id_pengguna', 'pengguna.username', 'pengguna.nm_pengguna', 'pengguna.status_join_table','role.nm_role', DB::raw("(SELECT COUNT(*) FROM role_pengguna WHERE role_pengguna.id_pengguna = pengguna.id_pengguna AND role_pengguna.deleted_at IS NULL) AS total_role"))
                        ->join('role_pengguna','role_pengguna.id_pengguna','=','pengguna.id_pengguna')
                        ->join('role', function ($join) {
                                        $join->on('role.id_role', '=', 'role_pengguna.id_role')
                                             ->where('role.is_mobile', '=', 1);
                                    })
                        ->where(function ($query) use ($username_nama_cari) {
                                $query->where('pengguna.nm_pengguna', 'like', '%'.$username_nama_cari.'%')
                                ->orWhere('pengguna.username', 'like', '%'.$username_nama_cari.'%');
                           })
                        ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('pengguna.nm_pengguna', 'asc')
                        ->get();

        return Datatables::of($pengguna)
                ->addColumn('status_join_table', function($item) {
                    if ($item->status_join_table == 1) {
                        return "Tenaga Pendidik";
                    }
                    elseif ($item->status_join_table == 2) {
                        return "Guru";
                    }
                    elseif ($item->status_join_table == 3) {
                        return "Siswa";
                    }
                    elseif ($item->status_join_table == 4) {
                        return "Wali Murid";
                    }
                    elseif ($item->status_join_table == 5) {
                        return "Pelatih Ekskul";
                    }
                })
                ->addColumn('multi_role', function($item) {
                    if ($item->total_role > 1) {
                        return $item->total_role;
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('action', function($item) use($username_nama_cari) {
                    $data = array(
                        'id' => $item->id_pengguna,
                        'id_cari' => $username_nama_cari
                    );
                    return $data;
                })
                ->make(true);
    }

    public function viewDetailPenggunaPencarian(Request $request, $id_pengguna, $username_nama_cari){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pengguna = Pengguna::select('pengguna.id_pengguna', 'pengguna.username', 'pengguna.nm_pengguna', 'pengguna.email_afiliasi', 'pengguna.email_pengguna', 'pengguna.nomor_hp_pengguna', 'pengguna.status_join_table', 'role_pengguna.id_role', 'pengguna.last_time_password', 'pengguna.last_time_login', 'pengguna.is_online', DB::raw("(SELECT COUNT(*) FROM role_pengguna WHERE role_pengguna.id_pengguna = pengguna.id_pengguna AND role_pengguna.deleted_at IS NULL) AS total_role"))
                  ->join('role_pengguna', function ($join) {
                                    $join->on('role_pengguna.id_pengguna', '=', 'pengguna.id_pengguna')
                                         ->where('role_pengguna.is_aktif', '=', 1);
                                })
                  ->where('pengguna.id_pengguna','=',$id_pengguna)
                  ->first();

        // convert format date
        if ( ! empty($pengguna->last_time_password)) {
            $last_time_password = strftime( "%A, %d %B %Y %H:%M:%S", strtotime($pengguna->last_time_password));
        }
        else {
            $last_time_password = " ";
        }
        if ( ! empty($pengguna->last_time_login)) {
            $last_time_login = strftime( "%A, %d %B %Y %H:%M:%S", strtotime($pengguna->last_time_login));
        }
        else {
            $last_time_login = " ";
        }

        if ($pengguna->is_online == 1) {
            $status_online = "Online";
        }
        elseif ($pengguna->is_online == 0) {
            $status_online = "Offline";
        }

        $role_pengguna_set = RolePengguna::select('role_pengguna.id_role_pengguna', 'role.id_role', 'role.nm_role')
                                ->join('role','role.id_role','=','role_pengguna.id_role')
                                ->where('id_pengguna','=',$id_pengguna)
                                ->orderBy('role.nm_role', 'asc')
                                ->get();
        
        $id_pengguna_auth = $auth_data->pengguna->id_pengguna;                                

        if ($pengguna->status_join_table == 1) {
            $unit_kerja_set  = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

            $unit_kerja_aktif = Staff::select('id_unit_kerja')->where('id_pengguna','=',$id_pengguna)->first();

            $id_unit_kerja = $unit_kerja_aktif->id_unit_kerja;

            $tipe_akun = "Tenaga Pendidik";

            return view('administrator/pengelolaan-akun/pencarian/view-detail-pencarian',compact('auth_data','id_pengguna','username_nama_cari','pengguna','role_pengguna_set','unit_kerja_set','id_unit_kerja','tipe_akun','last_time_password','last_time_login','status_online','id_pengguna_auth'));
        }
        elseif ($pengguna->status_join_table == 2) {
            $unit_kerja_set  = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

            $unit_kerja_aktif = Guru::select('id_unit_kerja')->where('id_pengguna','=',$id_pengguna)->first();

            $id_unit_kerja = $unit_kerja_aktif->id_unit_kerja;

            $tipe_akun = "Guru";

            return view('administrator/pengelolaan-akun/pencarian/view-detail-pencarian',compact('auth_data','id_pengguna','username_nama_cari','pengguna','role_pengguna_set','unit_kerja_set','id_unit_kerja','tipe_akun','last_time_password','last_time_login','status_online','id_pengguna_auth'));
        }
        else {
            if ($pengguna->status_join_table == 3) {
                $tipe_akun = "Siswa";
            }
            elseif ($pengguna->status_join_table == 4) {
                $tipe_akun = "Wali Murid";
            }
            elseif ($pengguna->status_join_table == 5) {
                $tipe_akun = "Pelatih Ekskul";
            }

            return view('administrator/pengelolaan-akun/pencarian/view-detail-pencarian',compact('auth_data','id_pengguna','username_nama_cari','pengguna','role_pengguna_set', 'tipe_akun','last_time_password','last_time_login','status_online','id_pengguna_auth'));
        }
    }

    public function datatablesRolePencarian(Request $request, $id_pengguna){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = RolePengguna::select('role_pengguna.id_role_pengguna', 'role.nm_role', 'role.deskripsi_role', 'role.path', 'role.is_mobile')
                                ->join('role','role.id_role','=','role_pengguna.id_role')
                                ->where('id_pengguna','=',$id_pengguna)
                                ->orderBy('role.nm_role', 'asc')
                                ->get();

        return Datatables::of($list_data)
                ->addColumn('action', function($item) {
                    $data = array(
                        'id' => $item->id_role_pengguna,
                        'is_mobile' => $item->is_mobile
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addRolePenggunaPencarian(Request $request, $id_pengguna, $username_nama_cari){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pengguna = Pengguna::select('id_pengguna', 'username', 'nm_pengguna')
                  ->where('id_pengguna','=',$id_pengguna)
                  ->first();

        $role_set = Role::select('id_role', 'nm_role', 'deskripsi_role', 'path')
                                ->whereNotIn('id_role',function($query) use($id_pengguna){
                                       $query->select('id_role')->from('role_pengguna')->where('id_pengguna','=',$id_pengguna)->whereNull('deleted_at');
                                    })
                                ->where('is_mobile','=',0)
                                ->orderBy('role.nm_role', 'asc')
                                ->get();

        return view('administrator/pengelolaan-akun/pencarian/add-role-pengguna',compact('auth_data','id_pengguna','username_nama_cari','pengguna', 'role_set'));
    }

    // Action POST
    public function actionPencarian(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_role'      => 'required'
        ]);
        
        if($validator->fails() && $mode == 'add-role-pengguna') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'edit') {

                $pengguna = Pengguna::find($id);

                if ($pengguna->status_join_table == 1) {
                    $staff = Staff::where('id_pengguna','=',$pengguna->id_pengguna)->first();

                    $staff                  = Staff::find($staff->id_staff);
                    $staff->id_unit_kerja   = $input->id_unit_kerja;
                    $staff->updated_by      = $input->auth_data->pengguna->id_pengguna;
                    $staff->updated_at      = $now;
                    $staff->save();
                }
                elseif ($pengguna->status_join_table == 2) {
                    $guru = Guru::where('id_pengguna','=',$pengguna->id_pengguna)->first();

                    $guru                  = Guru::find($guru->id_guru);
                    $guru->id_unit_kerja   = $input->id_unit_kerja;
                    $guru->updated_by      = $input->auth_data->pengguna->id_pengguna;
                    $guru->updated_at      = $now;
                    $guru->save();
                }

                if ( ! empty($input->id_role_pengguna)) {
                    $role_pengguna = RolePengguna::where('id_pengguna','=',$pengguna->id_pengguna)
                                        ->where('is_aktif','=',1)
                                        ->first();

                    $role_pengguna              = RolePengguna::find($role_pengguna->id_role_pengguna);
                    $role_pengguna->is_aktif    = 0;
                    $role_pengguna->updated_by  = $input->auth_data->pengguna->id_pengguna;
                    $role_pengguna->updated_at  = $now;
                    $role_pengguna->save();

                    $role_pengguna              = RolePengguna::find($input->id_role_pengguna);
                    $role_pengguna->is_aktif    = 1;
                    $role_pengguna->updated_by  = $input->auth_data->pengguna->id_pengguna;
                    $role_pengguna->updated_at  = $now;
                    $role_pengguna->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengelolaan-akun/pencarian/view-detail-pengguna/'.$pengguna->id_pengguna.'/'.$input->username_nama_cari,
                    'message' => 'Update Data successfully'
                ];
            }
            elseif ($mode == 'delete') {
                // make object to find id
                $rolePengguna               = RolePengguna::find($id);
                $rolePengguna->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $rolePengguna->save();

                $rolePengguna->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Role Pengguna successfully'
                ];
            }
            elseif ($mode == 'add-role-pengguna') {
                
                foreach ($input->id_role as $id_role) {
                    $rolePengguna                           = new RolePengguna;
                    $rolePengguna->id_role                  = $id_role;
                    $rolePengguna->id_pengguna              = $input->id_pengguna;
                    $rolePengguna->keterangan_role_pengguna = "Input Administrator";
                    $rolePengguna->is_aktif                 = 0;
                    $rolePengguna->created_by               = $input->auth_data->pengguna->id_pengguna;
                    $rolePengguna->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pengelolaan-akun/pencarian/view-detail-pengguna/'.$input->id_pengguna.'/'.$input->username_nama_cari,
                    'message' => 'Tambah Role Pengguna successfully'
                ];
            }
            elseif ($mode == 'reset-password') {

                $pengguna                       = Pengguna::find($input->id_pengguna);
                $pengguna->password             = Hash::make($pengguna->username);
                $pengguna->must_change_password = 1;
                $pengguna->last_time_password   = $now;
                $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $pengguna->updated_at           = $now;
                $pengguna->save();

                return [
                    'status' => 203,
                    'message' => 'Reset Password successfully'
                ];   
            }
        }
    }

    public function resetPasswordCollection(Request $request){
        $input = (object) $request->input();

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        try {

            foreach($input->data_pengguna as $id_pengguna){
                $pengguna                       = Pengguna::find($id_pengguna);
                $pengguna->password             = Hash::make($pengguna->username);
                $pengguna->must_change_password = 1;
                $pengguna->last_time_password   = $now;
                $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $pengguna->updated_at           = $now;
                $pengguna->save();
            }

            DB::commit();

            return response()->json([
                'status_code' 	=> 200,
                'status_text' 	=> 'Success',
                'message' => 'Reset some password account successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error. Error '.$e->getLine()
            ]);
        }
    }
}
