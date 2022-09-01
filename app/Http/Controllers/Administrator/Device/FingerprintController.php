<?php

namespace App\Http\Controllers\Administrator\Device;

use App\Models\FPAttendance;
use App\Models\FPDevice;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\Sekolah;
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
                );
                return $data;
            })
            ->make(true);
    }

    public function actionCheck(Request $request)
    {
        $input = (object) $request->input();

        // if (isset($input->SN)) {
        //     if ($device = FPDevice::where('sn', $input->SN)->first()) {
        //         if (isset($input->INFO)) {
        //             $ip_address_lan = explode(',', $input->INFO)[4];
        //             $device->ip_address_lan = $ip_address_lan;
        //         }
        //         $device->ip_address_wan = $request->ip();
        //         $device->updated_at = Carbon::now('Asia/Jakarta');
        //         $device->save();
        //     }
        // }

        return 'OK';
    }

    public function actionGetFinger(Request $request)
    {
        set_time_limit(9800);
        $input = (object) $request->input();
        $now = Carbon::now('Asia/Jakarta');

        $serial_number = '';
        if (isset($input->SN)) {
            $serial_number = $input->SN;
        }

        if ($device = FPDevice::where('sn', $serial_number)->first()) {
            // $device->ip_address_wan = $request->ip();
            // $device->updated_at = Carbon::now('Asia/Jakarta');
            // $device->save();
        } else {
            return 'FAILED';
        }

        $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";

        if (!empty($device->port)) {
            $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
        } else {
            $fingerprint_url = $device->ip_address_wan . '/iWsService';
        }
        try {
            $client = new \GuzzleHttp\Client();
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
            $data_fp = $this->filterData($buffer, $now->format('Y-m-d'));
   
            foreach ($data_fp as $data) {
                if ($item = FPAttendance::where('username', $data['username'])->where('fp_date',  $data['tanggal'])->first()) {

                } else {
                    $item = new FPAttendance;
                    $item->id_fp_device = $device->id_fp_device;
                    $item->username = $data['username'];
                    $item->status = $data['status'];
                    $item->tanggal =  $data['tanggal'];
                    $item->fp_date =  $data['tanggal'];
                    $item->save();
                }

                $prefix = Sekolah::first()->prefix;
                $now = Carbon::now('Asia/Jakarta');
                if ($pengguna = Pengguna::where('username', $item->username)->first()) {
                    if ($presensi = PresensiPengguna::where('id_pengguna', $pengguna->id_pengguna)->where('date', $data['tanggal'])->first()) {

                    } else {
                        $presensi = new PresensiPengguna;
                        $presensi->id_presensi_pengguna = $prefix . strtotime($now) . uniqid();
                        $presensi->id_pengguna = $pengguna->id_pengguna;
                        $presensi->status_join_table = $pengguna->status_join_table;
                        $presensi->date = $item->tanggal;
                    }

                    if ($item->status == 255) {
                        if (empty($presensi->check_in)) {
                            $presensi->check_in = $item->fp_date;
                        } else {
                            if (Carbon::parse($presensi->check_in)->diffInMinutes($item->fp_date) > 100) {
                                $presensi->check_out = $item->fp_date;
                            }
                        }
                    } else {
                        if ($item->status == 0) {
                            $presensi->check_in = $item->fp_date;
                        }

                        if ($item->status == 1) {
                            $presensi->check_out = $item->fp_date;
                        }
                    }
                    $presensi->status = null;
                    $presensi->notes = null;
                    $presensi->save();
                }
            }
            return 'OK';
        } catch (Exception $e) {
            return $e;
        }
    }

    public function syncDataFinger(Request $request){
        set_time_limit(9800);
        $input = (object) $request->input();
        $now = Carbon::now('Asia/Jakarta');
// dd($now);
        $serial_number = '';
        // if (isset($input->SN)) {
        //     $serial_number = $input->SN;
        // }


        // if ($device = FPDevice::where('sn', $serial_number)->first()) {
        //     // $device->ip_address_wan = $request->ip();
        //     // $device->updated_at = Carbon::now('Asia/Jakarta');
        //     // $device->save();
        // } else {
        //     return 'FAILED';
        // }

        $allDevice = FPDevice::all();
        
        foreach($allDevice as $device){
// dd($device);
        $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $device->comm_key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";

        if (!empty($device->port)) {
            $fingerprint_url = $device->ip_address_wan . ':' . $device->port . '/iWsService';
        } else {
            $fingerprint_url = $device->ip_address_wan . '/iWsService';
        }
        try {
            $client = new \GuzzleHttp\Client();
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
            $data_fp = $this->filterData($buffer, $now->format('Y-m-d'));
   
            foreach ($data_fp as $data) {
                if ($item = FPAttendance::where('username', $data['username'])->where('fp_date',  $data['tanggal'])->first()) {

                } else {
                    $item = new FPAttendance;
                    $item->id_fp_device = $device->id_fp_device;
                    $item->username = $data['username'];
                    $item->status = $data['status'];
                    $item->tanggal =  $data['tanggal'];
                    $item->fp_date =  $data['tanggal'];
                    $item->save();
                }

                $prefix = Sekolah::first()->prefix;
                $now = Carbon::now('Asia/Jakarta');
                if ($pengguna = Pengguna::where('username', $item->username)->first()) {
                    if ($presensi = PresensiPengguna::where('id_pengguna', $pengguna->id_pengguna)->where('date', $data['tanggal'])->first()) {

                    } else {
                        $presensi = new PresensiPengguna;
                        $presensi->id_presensi_pengguna = $prefix . strtotime($now) . uniqid();
                        $presensi->id_pengguna = $pengguna->id_pengguna;
                        $presensi->status_join_table = $pengguna->status_join_table;
                        $presensi->date = $item->tanggal;
                    }

                    if ($item->status == 255) {
                        if (empty($presensi->check_in)) {
                            $presensi->check_in = $item->fp_date;
                        } else {
                            if (Carbon::parse($presensi->check_in)->diffInMinutes($item->fp_date) > 100) {
                                $presensi->check_out = $item->fp_date;
                            }
                        }
                    } else {
                        if ($item->status == 0) {
                            $presensi->check_in = $item->fp_date;
                        }

                        if ($item->status == 1) {
                            $presensi->check_out = $item->fp_date;
                        }
                    }
                    $presensi->status = null;
                    $presensi->notes = null;
                    $presensi->save();
                }}
            
            return 'OK';
        } catch (Exception $e) {
            return $e;
        }}
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

    public function filterData($array, $tanggal_input)
    {
        $hasil = array();
        $counter = 0;

        foreach (array_reverse($array, true) as $key => $value) {
            if ($value) {
                $tanggal = $this->parseXMLData($value, "<DateTime>", "</DateTime>");
                $tanggal = date('Y-m-d', strtotime($tanggal));

                if ($tanggal == $tanggal_input) {
                    $hasil[$counter]['username'] = $this->parseXMLData($value, "<PIN>", "</PIN>");
                    $hasil[$counter]['tanggal'] = $this->parseXMLData($value, "<DateTime>", "</DateTime>");
                    $hasil[$counter]['status'] = $this->parseXMLData($value, "<Status>", "</Status>");
                    $counter++;
                } else {
                    continue;
                }
            }
        }

        return $hasil;
    }
}
