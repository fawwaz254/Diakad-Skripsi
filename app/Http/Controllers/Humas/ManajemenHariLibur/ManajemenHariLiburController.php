<?php

namespace App\Http\Controllers\Humas\ManajemenHariLibur;

use App\Models\ManajemenHariLibur;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class ManajemenHariLiburController extends BaseController
{

    public function viewManajemenHariLibur(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $holidays = [];
        $dates = ManajemenHariLibur::all();
        foreach ($dates as $key => $date) {
            $holidays[$key]['id'] = $date->manajemen_hari_libur_id;
            $holidays[$key]['year'] = Carbon::parse($date->date)->format('Y');
            $holidays[$key]['month'] = Carbon::parse($date->date)->format('M');
            $holidays[$key]['date'] = Carbon::parse($date->date)->format('d');
            $holidays[$key]['date_value'] = $date->date;
            $holidays[$key]['explanation'] = $date->explanation;
            $holidays[$key]['extra_money'] = $date->extra_money;
        }
        return view('humas/absensi/manajemen-hari-libur/view-manajemen-hari-libur', compact('holidays'));
    }


    public function createManajemenHariLibur(Request $request)
    {
        return view('humas/absensi/manajemen-hari-libur/add-manajemen-hari-libur');
    }

    public function storeManajemenHariLibur(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $is_holiday_exist = ManajemenHariLibur::where('date', $input->date)->first();
        if ($is_holiday_exist) {
            return [
                'status' => 200, // SUCCESS AND LOAD CONTENT
                'message' => 'Hari Libur sudah ada'
            ];
        }
        $now = Carbon::now();
        $prefix = Sekolah::first()->prefix;
        $holiday = new ManajemenHariLibur;
        $holiday->manajemen_hari_libur_id = $prefix . strtotime($now) . uniqid();
        $holiday->date = $input->date;
        $holiday->explanation = $input->explanation;
        $holiday->created_by = $auth_data->pengguna->id_pengguna;
        $holiday->save();
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/manajemen-hari-libur',
            'message' => 'Add Hari Libur Successfully'
        ];
    }

    public function editManajemenHariLibur(Request $request, $id)
    {
        $holiday = ManajemenHariLibur::where('manajemen_hari_libur_id', $id)->first();
        return view('humas/absensi/manajemen-hari-libur/edit-manajemen-hari-libur', compact('holiday'));
    }

    public function updateManajemenHariLibur(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $is_holiday_exist = ManajemenHariLibur::where('date', $input->date)->where('manajemen_hari_libur_id', '!=', $id)->first();
        if ($is_holiday_exist) {
            return [
                'status' => 200, // SUCCESS AND LOAD CONTENT
                'message' => 'Hari Libur sudah ada'
            ];
        }
        $is_holiday = ManajemenHariLibur::where('manajemen_hari_libur_id', $id)->first();
        $is_holiday->date = $input->date;
        $is_holiday->explanation = $input->explanation;
        $is_holiday->updated_by = $auth_data->pengguna->id_pengguna;
        $is_holiday->save();
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/manajemen-hari-libur',
            'message' => 'Add Hari Libur Successfully'
        ];
    }

    public function destroyManajemenHariLibur(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $holiday = ManajemenHariLibur::where('manajemen_hari_libur_id', $id)->first();
        $holiday->deleted_by = $auth_data->pengguna->id_pengguna;
        $holiday->delete();
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'absensi/manajemen-hari-libur',
            'message' => 'Delete Hari Libur Successfully'
        ];
    }
}
