<?php

namespace App\Http\Controllers\Administrator\Device;

use App\Jobs\CreateFPAttendences;
use App\Models\FPAttendance;
use App\Models\FPDevice;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yajra\Datatables\Datatables;

class FingerprintController extends BaseController
{
    public function indexList(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('administrator/device/fingerprint/view-data-fingerprint', compact('auth_data'));
    }

    public function commonList(Request $request)
    {
        $list_data = FPDevice::query();

        $now = Carbon::now('Asia/Jakarta');
        return Datatables::of($list_data)
            ->addColumn('status', function ($item) use ($now) {
                if (Carbon::parse($item->updated_at)->diffInMinutes($now) > 2) {
                    return 'OFFLINE';
                } else {
                    return 'ONLINE';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->fp_device_id,
                    'sn' => $item->sn,
                );
                return $data;
            })
            ->editColumn('updated_at', function ($item) use ($now) {
                return Carbon::parse($item->updated_at)->diffForHumans($now);
            })
            ->addColumn('last_data', function ($item) use ($now) {
                $fp_attendence =  FPAttendance::where('id_fp_device', $item->id_fp_device)->orderBy('fp_date', 'desc')->first();
                if ($fp_attendence) {
                    return Carbon::parse($fp_attendence->fp_date)->diffForHumans($now);
                } else {
                    return 'kosong';
                }
            })
            ->addColumn('clear_log', function ($item) use ($now) {
                if ($item->clear_log) {
                    return Carbon::parse($item->clear_log)->diffForHumans($now);
                } else {
                    return 'kosong';
                }
            })
            ->make(true);
    }

    public function actionCheck(Request $request)
    {
        $input = (object) $request->input();

        if (isset($input->SN)) {
            if ($device = FPDevice::where('sn', $input->SN)->first()) {
                // if (isset($input->INFO)) {
                //     $ip_address_lan = explode(',', $input->INFO)[4];
                //     $device->ip_address_lan = $ip_address_lan;
                // }
                // $device->ip_address_wan = $request->ip();
                $device->updated_at = Carbon::now('Asia/Jakarta');
                $device->save();
            }
        }

        return 'OK';
    }

    public function actionGetFinger(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $now = Carbon::now('Asia/Jakarta');
        $client = new \GuzzleHttp\Client();

        $serial_number = '';
        $date_filter = null;
        if (isset($input->SN)) {
            $serial_number = $input->SN;
        }

        if (isset($input->dd)) {
            $date_filter = $input->dd;
        }

        if ($device = FPDevice::where('sn', $serial_number)->first()) {
            // $device->ip_address_wan = $request->ip();
            $device->updated_at = Carbon::now('Asia/Jakarta');
            $device->save();
        } else {
            return 'FAILED';
        }

        $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";

        // Ping Fingerprint
        try {
            if (!empty($device->port)) {
                $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
            } else {
                $fingerprint_url = $device->ip_address_wan . '/iWsService';
            }
            $client->request('GET', $fingerprint_url, ['timeout' => 3.14]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return 'Failed';
        }


        $last_data = FPAttendance::where('id_fp_device', $device->id_fp_device)->orderBy('created_at', 'desc')->first();

        if ($last_data) {
            $last_time = Carbon::parse($last_data->created_at)->addMinutes(1)->format('Y-m-d H:i:s');
        } else {
            $last_time = $now->format('Y-m-d H:i:s');
        }

        if ($now->format('Y-m-d H:i:s') >= $last_time) {
            $data_username_pengguna = array();
            try {
                $response = $client->post($fingerprint_url, [
                    'headers' => [
                        'Content-Type' => 'text/xml',
                        'Content-Length' => strlen($soap_request),
                    ],
                    'body' => $soap_request,
                ]);

                $buffer = $response->getBody()->getContents();

                $buffer = $this->parseXMLData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
                $buffer = explode("\r\n", $buffer);


                if (!empty($date_filter)) {
                    $data_fp = $this->filterData($buffer, $date_filter);
                } else {
                    // if ($last_data) {
                    // $data_fp = $this->filterData($buffer, $now->format('Y-m-d'), $last_data->created_at);
                    // } else {
                    $data_fp = $this->filterData($buffer, $now->format('Y-m-d'));
                    // }
                }

                foreach ($data_fp as $data) {
                    $data_username_pengguna[] = $data['username'];
                    if ($serial_number == 'BWXP222860373' || $serial_number == 'BWXP222860377' || $serial_number == 'BWXP222860378') {
                        if ($item = FPAttendance::where('username', $data['username'])->where('fp_date', $data['tanggal'])->where('unit', 'Pondok')->first()) { } else {
                            $item = new FPAttendance;
                            $item->id_fp_device = $device->id_fp_device;
                            $item->username = $data['username'];
                            $item->status = $data['status'];
                            $item->tanggal = $data['tanggal'];
                            $item->fp_date = $data['tanggal'];
                            $item->unit = 'Pondok';
                            $item->save();
                        }
                    } else {
                        if ($item = FPAttendance::where('username', $data['username'])->where('fp_date', $data['tanggal'])->whereNull('unit')->first()) { } else {
                            $item = new FPAttendance;
                            $item->id_fp_device = $device->id_fp_device;
                            $item->username = $data['username'];
                            $item->status = $data['status'];
                            $item->tanggal = $data['tanggal'];
                            $item->fp_date = $data['tanggal'];
                            $item->save();
                        }
                    }
                }
                echo 'SUCCESS Tarik Data -> ';
            } catch (Exception $e) {
                return $e;
            }

            try {
                $data_fingerprint = FPAttendance::where('tanggal', $now->format('Y-m-d'))->whereNull('unit')->whereIn('username', $data_username_pengguna)->get();
                $collection = $data_fingerprint->groupBy('username')->all();

                foreach ($collection as $username => $group_of_data) {
                    if ($pengguna = Pengguna::where('username', $username)->first()) {
                        $first_time_finger = $group_of_data->sortBy('fp_date')->values()[0];

                        if ($presensi = PresensiPengguna::where('id_pengguna', $pengguna->id_pengguna)->where('date', $first_time_finger->tanggal)
                            ->whereNull('unit')
                            // ->where('status_join_table', $pengguna->status_join_table)
                            ->first()
                        ) { } else {
                            $presensi = new PresensiPengguna;
                            $presensi->id_pengguna = $pengguna->id_pengguna;
                            $presensi->status_join_table = $pengguna->status_join_table;
                            $presensi->date = $first_time_finger->tanggal;
                        }

                        if ($first_time_finger->status == 255) {
                            if (count($group_of_data) > 1) { // FINGER MORE THAN 1
                                $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                $last_time_finger = $group_of_data->sortByDesc('fp_date')->values()[0];
                                if (Carbon::parse($first_time_finger->fp_date)->diffInMinutes($last_time_finger->fp_date) > 100) {
                                    $presensi->check_out = date_format(date_create($last_time_finger->fp_date), 'H:i:s');
                                }
                            } else { // ONLY CHECK-IN
                                // if (empty($presensi->check_in)) {
                                $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                // }
                            }
                        } else {
                            if ($first_time_finger->status == 0) {
                                $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                            }

                            if ($first_time_finger->status == 1) {
                                $presensi->check_out = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                            }
                        }

                        if (!empty($presensi->check_in) && !empty($presensi->check_out)) {
                            if ($presensi->check_out < $presensi->check_in) {
                                $temp_clock = $presensi->check_in;

                                $presensi->check_in = $presensi->check_out;
                                $presensi->check_out = $temp_clock;
                            }
                        }
                        $presensi->status = null;
                        $presensi->notes = null;
                        $presensi->save();
                    }
                }
                //---------ambil data pondok-------------//
                $data_fingerprint2 = FPAttendance::where('tanggal', $now->format('Y-m-d'))->where('unit', 'Pondok')->whereIn('username', $data_username_pengguna)->get();
                if (!empty($data_fingerprint2)) {
                    $collection2 = $data_fingerprint2->groupBy('username')->all();

                    foreach ($collection2 as $username => $group_of_data) {
                        if ($pengguna = Pengguna::where('username', $username)->first()) {
                            $first_time_finger = $group_of_data->sortBy('fp_date')->values()[0];

                            if ($presensi = PresensiPengguna::where('id_pengguna', $pengguna->id_pengguna)->where('date', $first_time_finger->tanggal)
                                // ->whereNull('unit')
                                // ->where('status_join_table', '4')
                                ->where('unit', 'Pondok')
                                ->first()
                            ) { } else {
                                $presensi = new PresensiPengguna;
                                $presensi->id_pengguna = $pengguna->id_pengguna;
                                $presensi->status_join_table = $pengguna->status_join_table;
                                $presensi->date = $first_time_finger->tanggal;
                                $presensi->unit = "Pondok";
                            }

                            if ($first_time_finger->status == 255) {
                                if (count($group_of_data) > 1) { // FINGER MORE THAN 1
                                    $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                    $last_time_finger = $group_of_data->sortByDesc('fp_date')->values()[0];
                                    if (Carbon::parse($first_time_finger->fp_date)->diffInMinutes($last_time_finger->fp_date) > 100) {
                                        $presensi->check_out = date_format(date_create($last_time_finger->fp_date), 'H:i:s');
                                    }
                                } else { // ONLY CHECK-IN
                                    // if (empty($presensi->check_in)) {
                                    $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                    // }
                                }
                            } else {
                                if ($first_time_finger->status == 0) {
                                    $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                }

                                if ($first_time_finger->status == 1) {
                                    $presensi->check_out = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                }
                            }

                            if (!empty($presensi->check_in) && !empty($presensi->check_out)) {
                                if ($presensi->check_out < $presensi->check_in) {
                                    $temp_clock = $presensi->check_in;

                                    $presensi->check_in = $presensi->check_out;
                                    $presensi->check_out = $temp_clock;
                                }
                            }
                            $presensi->status = null;
                            $presensi->notes = null;
                            $presensi->save();
                        }
                    }
                }
                echo 'SUCCESS Merge Data. >>> END';
            } catch (Exception $e) {
                return $e;
            }
        } else {
            return 'Belum 5 menit';
        }
    }


    public function actionGetData(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        if (isset($input->dd)) {
            $date_filter = Carbon::parse($input->dd);
        } else {
            $date_filter = Carbon::now('Asia/Jakarta');
        }

        $now = Carbon::now('Asia/Jakarta');
        $client = new \GuzzleHttp\Client();

        $finger_sukses = 'Finger yang berhasil diambil = </br>';
        $devices = FPDevice::orderBy('updated_at', 'DESC')->get();
        foreach ($devices as $device) {
            $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
            try {
                if (!empty($device->port)) {
                    $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
                } else {
                    $fingerprint_url = $device->ip_address_wan . '/iWsService';
                }
                $client->request('GET', $fingerprint_url, ['timeout' => 3.14]);
                // if (!$response->getStatusCode() == 200) {
                //     continue;
                // }
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                continue;
            }

            $serial_number = $device->sn;
            // $data_username_pengguna = array();
            try {
                $response = $client->post($fingerprint_url, [
                    'headers' => [
                        'Content-Type' => 'text/xml',
                        'Content-Length' => strlen($soap_request),
                    ],
                    'body' => $soap_request,
                ]);

                $buffer = $response->getBody()->getContents();
                $buffer = $this->parseXMLData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
                $buffer = explode("\r\n", $buffer);
                $data_fp = $this->filterData($buffer, $date_filter->format('Y-m-d'));
                $fp_attendences = FPAttendance::whereDay('fp_date', $date_filter->format('d'))->where('id_fp_device', $device->id_fp_device)->get();
                foreach ($data_fp as $data) {
                    // $data_username_pengguna[] = $data['username'];
                    if ($serial_number == 'BWXP222860373' || $serial_number == 'BWXP222860377' || $serial_number == 'BWXP222860378') {

                        $dates =  Carbon::parse($date_filter);

                        $firstSubuh = Carbon::create($dates->year, $dates->month, $dates->day, 3, 55, 0);
                        $endSubuh = Carbon::create($dates->year, $dates->month, $dates->day, 4, 35, 0);

                        $firstDzuhur = Carbon::create($dates->year, $dates->month, $dates->day, 11, 55, 0);
                        $endDzuhur = Carbon::create($dates->year, $dates->month, $dates->day, 12, 35, 0);

                        $firstMaghrib = Carbon::create($dates->year, $dates->month, $dates->day, 17, 40, 0);
                        $endMaghrib = Carbon::create($dates->year, $dates->month, $dates->day, 18, 20, 0);

                        $firstIsya = Carbon::create($dates->year, $dates->month, $dates->day, 19, 10, 0);
                        $endIsya = Carbon::create($dates->year, $dates->month, $dates->day, 19, 50, 0);

                        $fpDate =  Carbon::parse($data['tanggal']);
                        if ($fpDate->between($firstSubuh, $endSubuh) || $fpDate->between($firstDzuhur, $endDzuhur) || $fpDate->between($firstMaghrib, $endMaghrib) || $fpDate->between($firstIsya, $endIsya)) {
                            if ($fp_attendences->where('username', $data['username'])->where('fp_date', $data['tanggal'])->where('unit', 'Sholat')->first()) { } else {
                                $list_data[] = [
                                    'id_fp_device' =>  $device->id_fp_device,
                                    'username' => $data['username'],
                                    'status' => $data['status'],
                                    'tanggal' => $data['tanggal'],
                                    'fp_date' => $data['tanggal'],
                                    'unit' => 'Sholat',
                                    'created_at' => $now,
                                ];
                            }
                        } else {
                            if ($fp_attendences->where('username', $data['username'])->where('fp_date', $data['tanggal'])->where('unit', 'Pondok')->first()) { } else {
                                $list_data[] = [
                                    'id_fp_device' =>  $device->id_fp_device,
                                    'username' => $data['username'],
                                    'status' => $data['status'],
                                    'tanggal' => $data['tanggal'],
                                    'fp_date' => $data['tanggal'],
                                    'unit' => 'Pondok',
                                    'created_at' => $now,
                                ];
                            }
                        }
                    } elseif ($serial_number == 'BWXP221661050' || $serial_number == 'BWXP221660109' || $serial_number == 'BWXP221660991' || $serial_number == 'BWXP221660110' || $serial_number == 'BWXP221660113') {
                        if ($fp_attendences->where('username', $data['username'])->where('fp_date', $data['tanggal'])->where('unit', 'Sholat')->first()) { } else {
                            $list_data[] = [
                                'id_fp_device' =>  $device->id_fp_device,
                                'username' => $data['username'],
                                'status' => $data['status'],
                                'tanggal' => $data['tanggal'],
                                'fp_date' => $data['tanggal'],
                                'unit' => 'Sholat',
                                'created_at' => $now,
                            ];
                        }
                    } else {
                        if ($fp_attendences->where('username', $data['username'])->where('fp_date', $data['tanggal'])->whereNull('unit')->first()) { } else {
                            $list_data[] = [
                                'id_fp_device' =>  $device->id_fp_device,
                                'username' => $data['username'],
                                'status' => $data['status'],
                                'tanggal' => $data['tanggal'],
                                'fp_date' => $data['tanggal'],
                                'created_at' => $now,
                            ];
                        }
                    }
                }

                if (!empty($list_data)) {
                    CreateFPAttendences::dispatch($list_data);
                    unset($list_data);
                }
                $finger_sukses = $finger_sukses . $serial_number . '</br>';
            } catch (Exception $e) {
                continue;
            }
        }
        echo $finger_sukses;
    }

    public function actionSyncData(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        if (isset($input->dd)) {
            $date_filter = Carbon::parse($input->dd);
        } else {
            $date_filter = Carbon::now('Asia/Jakarta');
        }

        try {
            $data_fingerprint = FPAttendance::where('tanggal', $date_filter->format('Y-m-d'))->whereNull('unit')->get();
            $collection = $data_fingerprint->groupBy('username')->all();
            $penggunas =  Pengguna::whereIn('username', collect($collection)->keys())->get();
            $presensis = PresensiPengguna::where('date', $date_filter->format('Y-m-d'))->whereNull('unit')->get();

            foreach ($collection as $username => $group_of_data) {
                if ($pengguna = $penggunas->where('username', $username)->first()) {
                    $first_time_finger = $group_of_data->sortBy('fp_date')->values()[0];

                    if ($presensi = $presensis->where('id_pengguna', $pengguna->id_pengguna)->first()) { } else {
                        $presensi = new PresensiPengguna;
                        $presensi->id_pengguna = $pengguna->id_pengguna;
                        $presensi->status_join_table = $pengguna->status_join_table;
                        $presensi->date = $first_time_finger->tanggal;
                    }

                    if ($first_time_finger->status == 255) {
                        if (count($group_of_data) > 1) { // FINGER MORE THAN 1
                            $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                            $last_time_finger = $group_of_data->sortByDesc('fp_date')->values()[0];
                            if (Carbon::parse($first_time_finger->fp_date)->diffInMinutes($last_time_finger->fp_date) > 100) {
                                $presensi->check_out = date_format(date_create($last_time_finger->fp_date), 'H:i:s');
                            }
                        } else { // ONLY CHECK-IN
                            // if (empty($presensi->check_in)) {
                            $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                            // }
                        }
                    } else {
                        if ($first_time_finger->status == 0) {
                            $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                        }

                        if ($first_time_finger->status == 1) {
                            $presensi->check_out = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                        }
                    }

                    if (!empty($presensi->check_in) && !empty($presensi->check_out)) {
                        if ($presensi->check_out < $presensi->check_in) {
                            $temp_clock = $presensi->check_in;

                            $presensi->check_in = $presensi->check_out;
                            $presensi->check_out = $temp_clock;
                        }
                    }
                    $presensi->status = null;
                    $presensi->notes = null;
                    $presensi->save();
                }
            }

            //--------------------------finger pondok-----------------------------//
            $data_fingerprint2 = FPAttendance::where('tanggal', $date_filter->format('Y-m-d'))->where('unit', 'Pondok')->get();
            if (!empty($data_fingerprint2)) {
                $collection2 = $data_fingerprint2->groupBy('username')->all();
                $penggunas =  Pengguna::whereIn('username', collect($collection)->keys())->get();
                $presensis = PresensiPengguna::where('date', $date_filter->format('Y-m-d'))->where('unit', 'Pondok')->get();
                foreach ($collection2 as $username => $group_of_data) {
                    if ($pengguna = $penggunas->where('username', $username)->first()) {
                        $first_time_finger = $group_of_data->sortBy('fp_date')->values()[0];

                        if ($presensi = $presensis->where('id_pengguna', $pengguna->id_pengguna)->where('date', $first_time_finger->tanggal)->first()
                            // ->where('status_join_table', '4')
                        ) { } else {
                            $presensi = new PresensiPengguna;
                            $presensi->id_pengguna = $pengguna->id_pengguna;
                            $presensi->status_join_table =  $pengguna->status_join_table;
                            $presensi->date = $first_time_finger->tanggal;
                            $presensi->unit = "Pondok";
                        }

                        if ($first_time_finger->status == 255) {
                            if (count($group_of_data) > 1) { // FINGER MORE THAN 1
                                $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                $last_time_finger = $group_of_data->sortByDesc('fp_date')->values()[0];
                                if (Carbon::parse($first_time_finger->fp_date)->diffInMinutes($last_time_finger->fp_date) > 100) {
                                    $presensi->check_out = date_format(date_create($last_time_finger->fp_date), 'H:i:s');
                                }
                            } else { // ONLY CHECK-IN
                                // if (empty($presensi->check_in)) {
                                $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                                // }
                            }
                        } else {
                            if ($first_time_finger->status == 0) {
                                $presensi->check_in = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                            }

                            if ($first_time_finger->status == 1) {
                                $presensi->check_out = date_format(date_create($first_time_finger->fp_date), 'H:i:s');
                            }
                        }

                        if (!empty($presensi->check_in) && !empty($presensi->check_out)) {
                            if ($presensi->check_out < $presensi->check_in) {
                                $temp_clock = $presensi->check_in;

                                $presensi->check_in = $presensi->check_out;
                                $presensi->check_out = $temp_clock;
                            }
                        }
                        $presensi->status = null;
                        $presensi->notes = null;
                        $presensi->save();
                    }
                }
            }


            return 'OK';
        } catch (Exception $e) {
            return $e;
        }
    }

    //     public function syncDataFinger(Request $request){
    //         set_time_limit(9800);
    //         $input = (object) $request->input();
    //         $now = Carbon::now('Asia/Jakarta');
    // // dd($now);
    //         $serial_number = '';
    //         // if (isset($input->SN)) {
    //         //     $serial_number = $input->SN;
    //         // }

    //         // if ($device = FPDevice::where('sn', $serial_number)->first()) {
    //         //     // $device->ip_address_wan = $request->ip();
    //         //     // $device->updated_at = Carbon::now('Asia/Jakarta');
    //         //     // $device->save();
    //         // } else {
    //         //     return 'FAILED';
    //         // }

    //         $allDevice = FPDevice::all();

    //         foreach($allDevice as $device){
    // // dd($device);
    //         $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";

    //         if (!empty($device->port)) {
    //             $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
    //         } else {
    //             $fingerprint_url = $device->ip_address_wan . '/iWsService';
    //         }
    //         try {
    //             $client = new \GuzzleHttp\Client();
    //             $response = $client->post($fingerprint_url, [
    //                 'headers' => [
    //                     'Content-Type' => 'text/xml',
    //                     'Content-Length' => strlen($soap_request),
    //                 ],
    //                 'body' => $soap_request,
    //             ]);

    //             $buffer = $response->getBody()->getContents();

    //             $buffer = $this->parseXMLData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
    //             $buffer = explode("\r\n", $buffer);
    //             $data_fp = $this->filterData($buffer, $now->format('Y-m-d'));

    //             foreach ($data_fp as $data) {
    //                 if ($item = FPAttendance::where('username', $data['username'])->where('fp_date',  $data['tanggal'])->first()) {

    //                 } else {
    //                     $item = new FPAttendance;
    //                     $item->id_fp_device = $device->id_fp_device;
    //                     $item->username = $data['username'];
    //                     $item->status = $data['status'];
    //                     $item->tanggal =  $data['tanggal'];
    //                     $item->fp_date =  $data['tanggal'];
    //                     $item->save();
    //                 }

    //                 $prefix = Sekolah::first()->prefix;
    //                 $now = Carbon::now('Asia/Jakarta');
    //                 if ($pengguna = Pengguna::where('username', $item->username)->first()) {
    //                     if ($presensi = PresensiPengguna::where('id_pengguna', $pengguna->id_pengguna)->where('date', $data['tanggal'])->first()) {

    //                     } else {
    //                         $presensi = new PresensiPengguna;
    //                         $presensi->id_presensi_pengguna = $prefix . strtotime($now) . uniqid();
    //                         $presensi->id_pengguna = $pengguna->id_pengguna;
    //                         $presensi->status_join_table = $pengguna->status_join_table;
    //                         $presensi->date = $item->tanggal;
    //                     }

    //                     if ($item->status == 255) {
    //                         if (empty($presensi->check_in)) {
    //                             $presensi->check_in = $item->fp_date;
    //                         } else {
    //                             if (Carbon::parse($presensi->check_in)->diffInMinutes($item->fp_date) > 100) {
    //                                 $presensi->check_out = $item->fp_date;
    //                             }
    //                         }
    //                     } else {
    //                         if ($item->status == 0) {
    //                             $presensi->check_in = $item->fp_date;
    //                         }

    //                         if ($item->status == 1) {
    //                             $presensi->check_out = $item->fp_date;
    //                         }
    //                     }
    //                     $presensi->status = null;
    //                     $presensi->notes = null;
    //                     $presensi->save();
    //                 }}

    //             return 'OK';
    //         } catch (Exception $e) {
    //             return $e;
    //         }}
    //     }

    public function parseXMLData($data, $p1, $p2)
    {
        $data = " " . $data;
        $hasil = "";
        $awal = strpos($data, $p1);
        if ($awal != "") {
            $akhir = strpos(strstr($data, $p1), $p2);
            if ($akhir != "") {
                $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
            }
        }
        return $hasil;
    }


    public function viewDataFinger(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $now = Carbon::now('Asia/Jakarta');
        $client = new \GuzzleHttp\Client();

        if (isset($input->dd)) {
            $date_filter = Carbon::parse($input->dd);
        } else {
            $date_filter = Carbon::now('Asia/Jakarta');
        }

        $serial_number = '';
        if (isset($input->SN)) {
            $serial_number = $input->SN;
        }

        if ($device = FPDevice::where('sn', $serial_number)->first()) {
            // $device->ip_address_wan = $request->ip();
            $device->updated_at = Carbon::now('Asia/Jakarta');
            $device->save();
        } else {
            return 'FAILED';
        }

        $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";

        try {
            if (!empty($device->port)) {
                $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
            } else {
                $fingerprint_url = $device->ip_address_wan . '/iWsService';
            }
            $client->request('GET', $fingerprint_url);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return 'Failed';
        }

        try {
            $response = $client->post($fingerprint_url, [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'Content-Length' => strlen($soap_request),
                ],
                'body' => $soap_request,
            ]);

            $buffer = $response->getBody()->getContents();

            $buffer = $this->parseXMLData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
            $buffer = explode("\r\n", $buffer);
        } catch (Exception $e) {
            return $e;
        }

        $data = $this->filterData($buffer, $date_filter->format('Y-m-d'));

        if (count($data) == '0') {
            echo 'Data Kosong';
        } else {
            echo 'Total Data = ' . count($data) . '<br/>';
            foreach ($data as $d) {
                echo $d['tanggal'] . ' | ' . $d['username'] . '<br/>';
            }
        }
    }


    public function filterData($array, $tanggal_input, $datetime_mulai = null)
    {
        $hasil = array();
        $counter = 0;

        foreach (array_reverse($array, true) as $key => $value) {
            if ($value) {
                $tanggal = $this->parseXMLData($value, "<DateTime>", "</DateTime>");

                if (empty($datetime_mulai)) {
                    $tanggal = date('Y-m-d', strtotime($tanggal));

                    if ($tanggal == $tanggal_input) {
                        $hasil[$counter]['username'] = $this->parseXMLData($value, "<PIN>", "</PIN>");
                        $hasil[$counter]['tanggal'] = $this->parseXMLData($value, "<DateTime>", "</DateTime>");
                        $hasil[$counter]['status'] = $this->parseXMLData($value, "<Status>", "</Status>");
                        $counter++;
                    } else {
                        continue;
                    }
                } else {
                    $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal);

                    if ($tanggal->gt(Carbon::createFromFormat('Y-m-d H:i:s', $datetime_mulai))) {
                        $hasil[$counter]['username'] = $this->parseXMLData($value, "<PIN>", "</PIN>");
                        $hasil[$counter]['tanggal'] = $this->parseXMLData($value, "<DateTime>", "</DateTime>");
                        $hasil[$counter]['status'] = $this->parseXMLData($value, "<Status>", "</Status>");
                        $counter++;
                    } else {
                        continue;
                    }
                }
            }
        }

        return $hasil;
    }

    public function clearLogData(Request $request)
    {
        $input = (object) $request->input();
        $client = new \GuzzleHttp\Client();

        $serial_number = '';
        if (isset($input->SN)) {
            $serial_number = $input->SN;
        }

        if ($device = FPDevice::where('sn', $serial_number)->first()) { } else {
            return 'FAILED';
        }

        $soap_request = "<ClearData><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";

        try {
            if (!empty($device->port)) {
                $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
            } else {
                $fingerprint_url = $device->ip_address_wan . '/iWsService';
            }
            // $client->request('GET', $fingerprint_url);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return 'Failed';
        }

        try {
            $res = $client->post($fingerprint_url, [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'Content-Length' => strlen($soap_request)
                ],
                'body' => $soap_request
            ]);
            $device->clear_log = Carbon::now('Asia/Jakarta');
            $device->save();
            echo "Berhasil";
        } catch (\Exception $e) {
            echo "Koneksi Gagal";
        }
    }

    // public function clearLogDataAll(Request $request)
    // {
    //     $input = (object) $request->input();
    //     $client = new \GuzzleHttp\Client();

    //     // $serial_number = '';
    //     // $date_filter = null;
    //     // if (isset($input->SN)) {
    //     //     $serial_number = $input->SN;
    //     // }

    //     if ($device = FPDevice::all()) {
    //         // $device->ip_address_wan = $request->ip();
    //         $device->updated_at = Carbon::now('Asia/Jakarta');
    //         $device->save();
    //     } else {
    //         return 'FAILED';
    //     }

    //     $soap_request = "<ClearData><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";

    //     try {
    //         if (!empty($device->port)) {
    //             $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
    //         } else {
    //             $fingerprint_url = $device->ip_address_wan . '/iWsService';
    //         }
    //         // $client->request('GET', $fingerprint_url);
    //     } catch (\GuzzleHttp\Exception\GuzzleException $e) {
    //         return 'Failed';
    //     }

    //     try {
    //         $res = $client->post($fingerprint_url, [
    //             'headers' => [
    //                 'Content-Type' => 'text/xml',
    //                 'Content-Length' => strlen($soap_request)
    //             ],
    //             'body' => $soap_request
    //         ]);
    //         echo "Berhasil";
    //     } catch (\Exception $e) {
    //         echo "Koneksi Gagal";
    //     }
    // }
}
