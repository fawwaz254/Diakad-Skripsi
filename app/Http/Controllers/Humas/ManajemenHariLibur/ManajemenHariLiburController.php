<?php

namespace App\Http\Controllers\Humas\ManajemenHariLibur;

use App\Models\ManajemenHariLibur;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;

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
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $prefix = Sekolah::first()->prefix;
        $uuid = $prefix . strtotime($now) . uniqid();
        $holiday = [];
        $is_holiday_exist = ManajemenHariLibur::where('date', $input->date)->first();
        if ($is_holiday_exist) {
            return redirect("/humas#absensi/manajemen-hari-libur/add");
        }
        $holiday['manajemen_hari_libur_id'] = $uuid;
        $holiday['date'] = $input->date;
        $holiday['explanation'] = $input->explanation;
        $holiday['created_by'] = $auth_data->pengguna->id_pengguna;
        ManajemenHariLibur::create($holiday);
        return redirect("/humas#absensi/manajemen-hari-libur");
    }

    public function editManajemenHariLibur(Request $request, $id)
    {
        $holiday = ManajemenHariLibur::where('manajemen_hari_libur_id', $id)->first();
        return view('humas/absensi/manajemen-hari-libur/edit-manajemen-hari-libur', compact('holiday'));
    }

    public function updateManajemenHariLibur(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $is_holiday_exist = ManajemenHariLibur::where('date', $input->date)->where('manajemen_hari_libur_id','!=',$id)->first();
        if ($is_holiday_exist) {
            return redirect("/humas#absensi/manajemen-hari-libur/" . $id . "/edit");
        }
        $is_holiday = ManajemenHariLibur::where('manajemen_hari_libur_id', $id)->first();
        $holiday = [];
        $holiday['date'] = $input->date;
        $holiday['explanation'] = $input->explanation;
        $holiday['updated_by'] = $auth_data->pengguna->id_pengguna;
        $is_holiday->update($holiday);
        return redirect("/humas#absensi/manajemen-hari-libur");
    }

    public function destroyManajemenHariLibur(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $is_holiday = ManajemenHariLibur::where('manajemen_hari_libur_id', $id);
        $is_holiday->update(['deleted_by' => $auth_data->pengguna->id_pengguna]);
        $is_holiday->delete();
    }
}
