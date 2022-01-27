<?php

namespace App\Http\Controllers\Humas\ManajemenHariLibur;

use App\Models\ManajemenHariLibur;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ManajemenHariLiburController extends BaseController
{

    public function viewManajemenHariLibur(Request $request)
    {
        # code...
        $input = (object) $request->input();
        // $holidays = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();
        $holidays = [];
        $dates = ManajemenHariLibur::all();
        foreach ($dates as $key => $date) {
            $holidays[$key]['year'] = Carbon::parse($date->date)->format('Y');
            $holidays[$key]['month'] = Carbon::parse($date->date)->format('M');
            $holidays[$key]['holidays'] = Carbon::parse($date->date)->format('d');
            $holidays[$key]['date'] = $date->date;
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
        $holiday['manajemen_hari_libur_id'] = $uuid;
        $holiday['date'] = $input->date;
        $holiday['explanation'] = $input->explanation;
        $holiday['extra_money'] = $input->extraMoney;
        $holiday['created_by'] = $auth_data->pengguna->id_pengguna;
        ManajemenHariLibur::create($holiday);
        return redirect("/humas#absensi/manajemen-hari-libur");
    }

    public function editManajemenHariLibur(Request $request, $date = null)
    {
        $holiday = ManajemenHariLibur::where('date', $date)->first();
        return view('humas/absensi/manajemen-hari-libur/edit-manajemen-hari-libur', compact('holiday'));
    }

    public function updateManajemenHariLibur(Request $request, $date = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $is_holiday = ManajemenHariLibur::where('date', $date)->first();
        $holiday = [];
        $holiday['date'] = $input->date;
        $holiday['explanation'] = $input->explanation;
        $holiday['extra_money'] = $input->extraMoney;
        $holiday['updated_by'] = $auth_data->pengguna->id_pengguna;
        $is_holiday->update($holiday);
        return redirect("/humas#absensi/manajemen-hari-libur");
    }

    public function destroyManajemenHariLibur(Request $request, $date = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $is_holiday = ManajemenHariLibur::where('date', $date);
        $is_holiday->update(['deleted_by' => $auth_data->pengguna->id_pengguna]);
        $is_holiday->delete();
    }
}
