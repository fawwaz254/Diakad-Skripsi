<?php

namespace App\Http\Controllers\Humas\Absensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jalur;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\Siswa;
use App\Models\StatusPengguna;
use Illuminate\Support\Carbon;

class HistoriAbsensiSiswaController extends Controller
{
    public function viewHistoriAbsensiSiswa(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_alpha = 0;
        return view('humas/absensi/histori-absensi-siswa/view-histori-absensi-siswa',compact('auth_data','kelas','date','jumlah_hadir','jumlah_sakit','jumlah_izin','jumlah_telat','jumlah_alpha'));

    }

    public function actionDetailHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        if($input->kelas == '0') {
            return [
                'status' => 300, // FAILED
                'message' => 'Pilih Kelas Dahulu'
            ];
        }else{
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'absensi/histori-absensi-siswa/detail/'.$input->kelas.'/'.$input->date
            ];
        }
        
    }

    public function viewDetailHistoriAbsensiSiswa(Request $request, $id_kelas, $date){
    
        $input = (object) $request->input();

    
        $auth_data = $input->auth_data;

        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        $cek_libur = ManajemenHariLibur::where('date', $date)->first();


        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_alpha = 0;
        // dd($id_kelas );
        $pengguna = Siswa::where('id_kelas',$id_kelas)->with('pengguna')->with('pengguna.status_pengguna')
        ->whereHas('pengguna.status_pengguna', function ($query)  {
           $query->where('nm_status_pengguna', '=', 'AKTIF');
        })->get();


        foreach ($pengguna as $key => $value) {
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key]['status_join_table'] = $value->status_join_table;
            $hasil[$key]['nm_pengguna'] = $value->pengguna->nm_pengguna;
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';

            $hasil[$key]['id_presensi_pengguna'] = "";
            $attendance = PresensiPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $value->id_pengguna)->where('date', $date)->first();
            if (isset($shiftPengguna['start_time'])) {
                $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            } else {
                $shiftMaster = NULL;
            }
            $hasil[$key]['shift'] = false;
            if ($shiftPengguna) {
                $hasil[$key]['shift'] = true;
            }

            if ($attendance) {
                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }

                if ($attendance->id_presensi_pengguna) {
                    $hasil[$key]['id_presensi_pengguna'] = $attendance->id_presensi_pengguna;
                }

                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = $attendance->check_in;
                    $jumlah_hadir++;
                }

                if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                    $jumlah_telat++;
                    $hasil[$key]['status'] = "Masuk | Telat";
                }

                if (!$shiftMaster['start_time'] == null && $attendance->check_in >= $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out != NULL) {
                    $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = $attendance->check_out;
                }

                if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                    $hasil[$key]['status'] = "Masuk | Telat  | Tidak Checkout ";
                }

                // if ($attendance->notes) {
                //     $hasil[$key]['notes'] = $attendance->notes;
                // }
            } else {

                if ($shiftMaster) {

                    if ($date < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else if ($date == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }

                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
                // $hasil[$key]['notes'] = $cek_libur->explanation;
            }
        }

        return view('humas/absensi/histori-absensi-siswa/detail-histori-absensi-siswa',compact('auth_data','kelas','date','jumlah_hadir','jumlah_sakit','jumlah_izin','jumlah_telat','jumlah_alpha','pengguna','hasil'));


   
        // $nm_kelas = Kelas::where('id_kelas',$kelas)->first();
        // dd($nm_kelas);

        // $siswa = Siswa::where('id_kelas',$input->kelas)->get();
        // dd($siswa);
        // $pengguna = Pengguna::whereIn('status_join_table', 3)
        // ->with('status_pengguna','siswa')
        // ->whereHas('status_pengguna', function ($query) {
        //     $query->where('nm_status_pengguna', '=', 'AKTIF');
        // })
        // ->whereHas('siswa', function ($query) use($kelas) {
        //     $query->where('id_kelas', '=', $kelas);
        // })->get();


    }



    // public function actionViewDataSiswa(Request $request){
    //     # code...
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;
    //     $validator = Validator::make($request->all(), [
           
    //     ]);
  
    //     if($validator->fails()) {
    //         return [
    //             'status' => 300, // FAILED
    //             'message' => $validator->errors()->first()
    //         ];
    //     }
    //     else {
    //         return [
    //                   'status' => 204, // SUCCESS AND LOAD CONTENT
    //                   'path' => 'siswa/data-siswa/view-detail-data-siswa/'.$input->kelas.'/'.$input->date
    //               ];
    //        }
    //     }



    // public function getKelas($id_jurusan)
    // {
    //     $kelas = Kelas::where('id_jurusan','=',$id_jurusan)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
    //     return response()->json($kelas);
    // }
}
