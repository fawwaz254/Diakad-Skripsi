<?php

namespace App\Http\Controllers\Humas\ShiftPengguna;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShiftMaster;


class ShiftPenggunaMasterController extends Controller
{
    // public function addShiftMaster()
    // {
    //     return view('humas/absensi/shift-pengguna/add-shift-master');
    // }

    public function storeShiftMaster(Request $request)
    {


        $input = (object) $request->input();

        $list_data['id_shift_master'] = $input->name;
        $list_data['type'] = $input->name;
        $list_data['code'] =  $input->name;
        $list_data['start_time'] = $input->check_in;
        $list_data['end_time'] = $input->check_out;

        ShiftMaster::create($list_data);

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/shift_pengguna/managementShift',
            'message' => 'Save Shift successfully'
        ];


        // return redirect("/humas#absensi/shift_pengguna/managementShift");
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
}
