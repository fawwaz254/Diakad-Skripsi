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
        $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $dates = CarbonPeriod::create($start_date, $end_date);
        $date = [];
        $holidays = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();
        foreach ($dates as $key => $value) {
            $date[$key]['manajemen_hari_libur_id'] = '';
            $date[$key]['year'] = $value->format('Y');
            $date[$key]['month'] = $value->format('M');
            $date[$key]['date'] = $value->format('d');
            $date[$key]['date_value'] = $value->format('Y-m-d');
            $date[$key]['explanation'] = '-';
            $date[$key]['extra_money'] = '-';
            $holiday = $holidays->where('date', $value->format('Y-m-d'))->first();
            if ($holiday) {

                if ($holiday->manajemen_hari_libur_id) {
                    $date[$key]['manajemen_hari_libur_id'] = $holiday->manajemen_hari_libur_id;
                }

                if ($holiday->explanation) {
                    $date[$key]['explanation'] = $holiday->explanation;
                }

                if ($holiday->extra_money) {
                    $date[$key]['extra_money'] = $holiday->extra_money;
                }
            }
        }
        return view('humas/absensi/manajemen-hari-libur/view-manajemen-hari-libur', compact('date'));
    }

    public function showManajemenHariLibur(Request $request, $date = null)
    {
        $input = (object) $request->input();
        $curr_date = Carbon::parse($date);
        $is_holiday = ManajemenHariLibur::where('date', $date)->first();
        $column = [];
        $column['manajemen_hari_libur_id'] = '';
        $column['year'] = $curr_date->format('Y');
        $column['month'] = $curr_date->format('M');
        $column['date'] = $curr_date->format('d');
        $column['date_value'] = $date;
        $column['explanation'] = '-';
        $column['extra_money'] = '-';
        if ($is_holiday) {
            $holiday = $is_holiday->where('date', $curr_date->format('Y-m-d'))->first();
            if ($holiday->manajemen_hari_libur_id) {
                $column['manajemen_hari_libur_id'] = $holiday->manajemen_hari_libur_id;
            }

            if ($holiday->explanation) {
                $column['explanation'] = $holiday->explanation;
            }

            if ($holiday->extra_money) {
                $column['extra_money'] = $holiday->extra_money;
            }
        }
        return view('humas/absensi/manajemen-hari-libur/view-date-manajemen-hari-libur', compact('column', 'date'));
    }

    public function createManajemenHariLibur(Request $request, $date = null)
    {
        return view('humas/absensi/manajemen-hari-libur/add-manajemen-hari-libur');
    }

    public function storeManajemenHariLibur(Request $request, $date = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $prefix = Sekolah::first()->prefix;
        $uuid = $prefix . strtotime($now) . uniqid();
        $holiday = [];
        $holiday['manajemen_hari_libur_id'] = $uuid;
        $holiday['date'] = $date;
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
