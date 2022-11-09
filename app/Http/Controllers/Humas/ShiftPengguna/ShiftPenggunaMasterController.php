<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShiftMaster;
use Carbon\Carbon;
use App\Models\Sekolah;

class ShiftPenggunaMasterController extends Controller
{

    public function storeShiftMaster(Request $request)
    {

        $input = (object) $request->input();
        $validasiNama = ShiftMaster::where('code', $input->name)->first();
        if ($validasiNama) {
            return [
                'status' => 300, // SUCCESS AND LOAD CONTENT

                'message' => 'Nama tidak boleh sama'
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $prefix = Sekolah::first()->prefix;
            $html = $prefix . strtotime($now) . uniqid();

            $list_data['id_shift_master'] = $html;
            $list_data['type'] = $input->name;
            $list_data['code'] =  $input->name;
            $list_data['start_time'] = $input->check_in;
            $list_data['end_time'] = $input->check_out;

            ShiftMaster::create($list_data);

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'absensi/shift_pengguna/managementShift',
                'message' => 'Save Shift Successfully'
            ];
        }
    }

    public function destroyShiftMaster(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $is_holiday = ShiftMaster::where('id_shift_master', $id);
        $is_holiday->update(['deleted_by' => $auth_data->pengguna->id_pengguna]);
        $is_holiday->delete();
    }

    public function viewShiftPenggunaManagement()
    {
        $shifts = ShiftMaster::all();

        return view('humas/absensi/shift-pengguna/management-shift-master', compact('shifts'));
    }

    public function editShiftMaster(Request $request, $id){
        $shift = ShiftMaster::find($id);
        return view('humas/absensi/shift-pengguna/edit-management-shift-master', compact('shift'));
    }

    
    public function storeEditShiftMaster(Request $request, $id){
        // dd($id);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $shift = ShiftMaster::where('id_shift_master', $id)->first();
        $shift->start_time = $input->check_in ;
        $shift->end_time = $input->check_out; 
        $shift->save();
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/shift_pengguna/managementShift',
            'message' => 'Save Shift Successfully'
        ];
    
        // $shift = ShiftMaster::find($id);
        // return view('humas/absensi/shift-pengguna/edit-management-shift-master', compact('shift'));
    }
}
