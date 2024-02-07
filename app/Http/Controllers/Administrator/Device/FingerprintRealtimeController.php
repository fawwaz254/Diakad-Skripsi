<?php

namespace App\Http\Controllers\Administrator\Device;

use App\Http\Controllers\Controller;
use App\Models\FPAttendance;
use App\Models\PresensiPengguna;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Jobs\CreateFPAttendences;
use App\Models\FPDevice;
use App\Models\Pengguna;

class FingerprintRealtimeController extends Controller
{
    public function viewFingerprintRealtime(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('administrator/device/fingerprintRealtime/view-fingerprint-realtime', compact('auth_data'));
    }

    public function datatableFingerprintRealtime(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $date = $now->format("Y-m-d");
        $list_data = PresensiPengguna::with([
            'shiftPengguna' => function ($query) use ($date) {
                $query->where('date', $date)->with('shift_master');
            },
            'pengguna'
        ])->take('10')->orderBy('updated_at', 'desc');

        return Datatables::of($list_data)
            ->editColumn('pengguna.path_foto_pengguna', function ($item) {
                if (!empty($item->pengguna->path_foto_pengguna)) {
                    return Storage::disk('spaces')->url($item->pengguna->path_foto_pengguna);
                } else {
                    return asset('media/blank-user.png');
                }
            })
            ->editColumn('updated_at', function ($item) use ($now) {
                return Carbon::parse($item->updated_at)->diffForHumans($now);
                // return Carbon::parse($item->updated_at)->format('H:i:s');
            })
            ->addColumn(
                'status',
                function ($item) use ($date) {
                    if ($item->notes) {
                        $data = $item->notes;
                    } elseif ($item->shiftPengguna && $item->shiftPengguna->shift_master) {
                        $data = '';
                        if ($item->check_in) {
                            $data = "Masuk";
                        }
                        if (!$item->shiftPengguna->shift_master->start_time == null && $item->check_in > $item->shiftPengguna->shift_master->start_time) {
                            $data = "Masuk | Telat";
                        }
                        if ($item->check_out) {
                            if ($item->check_out < $item->shiftPengguna->shift_master->end_time && $item->check_out > $item->check_in) {
                                $data = "Masuk | Pulang lebih awal";
                            }
                        }
                        if ($item->shiftPengguna->shift_master->start_time && $item->check_in >= $item->shiftPengguna->shift_master->start_time && $item->check_out <  $item->shiftPengguna->shift_master->end_time && $item->check_out != NULL) {
                            $data = "Masuk | Telat dan Pulang lebih awal";
                        }
                        if ($date < Carbon::now()->format('Y-m-d') && $item->check_in && !$item->check_out) {
                            $data = 'Masuk | Tidak Checkout';
                        }
                        if (isset($item->shiftPengguna->shift_master->start_time)) {
                            if (!$item->shiftPengguna->shift_master->start_time == null && $item->check_in > $item->shiftPengguna->shift_master->start_time && !$item->check_out && $date < Carbon::now()->format('Y-m-d')) {
                                $data = "Masuk | Telat | Tidak Checkout";
                            }
                        }
                    } else {
                        $data = 'Tidak Punya Shift';
                    }
                    return $data;
                }
            )

            ->make(true);
    }


    public function getDataFingerprintRealtime(Request $request)
    {
        set_time_limit(-1);
        // $input = (object) $request->input();

        $now = Carbon::now('Asia/Jakarta');
        $date_filter = $now;
        $client = new \GuzzleHttp\Client();
        $finger_sukses = '';
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
                $finger_sukses =  $finger_sukses . ' ' . $serial_number;
            } catch (Exception $e) {
                continue;
            }
        }
        return $finger_sukses;
    }

    public function syncDataFingerprintRealtime(Request $request)
    {
        set_time_limit(-1);
        $now = Carbon::now('Asia/Jakarta');
        $date_filter = $now;
        // $last_check_in = PresensiPengguna::where('date', $date_filter->format('Y-m-d'))->orderBy('check_in', 'desc')->first();
        // $last_check_out = PresensiPengguna::where('date', $date_filter->format('Y-m-d'))->orderBy('check_out', 'desc')->first();
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
            // return 'OK';
        } catch (Exception $e) {
            // return false;
            // return $e;
        }
        // $last_check_in2 = PresensiPengguna::where('date', $date_filter->format('Y-m-d'))->orderBy('check_in', 'desc')->first();
        // $last_check_out2 = PresensiPengguna::where('date', $date_filter->format('Y-m-d'))->orderBy('check_out', 'desc')->first();


        // if ($last_check_in->check_in != $last_check_in2->check_in) {
        //     return true;
        // }

        // if ($last_check_out->check_out !=  $last_check_out2->check_out) {
        //     return true;
        // }

        return true;
    }
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

    public function getDataBarcodeFingerprint(Request $request)
    {


        return view('administrator/device/fingerprintBarcode/view-fingerprint-barcode');
    }
}
