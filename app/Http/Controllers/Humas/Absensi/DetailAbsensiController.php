<?php

namespace App\Http\Controllers\Humas\Absensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Exports\DetailAbsensi;
use Yajra\Datatables\Datatables;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\Siswa as Siswa;
use App\Models\PresensiPengguna;
use App\Models\ManajemenHariLibur;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\UnitKerja;
use Auth;
use DB;
use Session;
use Validator;

class DetailAbsensiController extends Controller
{

    public function selectHistoriAbsensi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_unit_kerja = UnitKerja::all();
        // $pengguna = Guru
        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        return view('humas/absensi/detail-absensi/select-detail-absensi', compact('auth_data', 'start_date', 'end_date', 'list_unit_kerja'));
    }

    public function actionGetPengguna(Request $request)
    {
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;

        if ($input->unit_kerja == "1") {
            $pengguna = pengguna::where('status_join_table', 1)->where('username', '!=', 'admin')
                ->with('status_pengguna')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->orderBy('nm_pengguna')
                ->get();
        } else {
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
                ->with('status_pengguna', 'guru.unit_kerja')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('guru.unit_kerja', function ($query) use ($input) {
                    $query->where('id_unit_kerja', '=', $input->unit_kerja);
                })->orderBy('nm_pengguna')
                ->get();
        }
        return $pengguna;
    }

    public function viewHistoriAbsensi(Request $request, $pengguna = null, $start_date = null, $end_date = null)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_unit_kerja = UnitKerja::all();
        $nm_pengguna = Pengguna::where('id_pengguna', $pengguna)->pluck('nm_pengguna')->first();

        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);

        $presences = PresensiPengguna::where('id_pengguna', $pengguna)->whereBetween('date', [$start_date, $end_date])->get();

        $hasil = [];

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;


        $hariIndo = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        foreach ($dates as $key => $value) {

            $hasil[$key]['tanggal'] = $value->format('d');
            $hasil[$key]['hari'] = $hariIndo[$value->dayOfWeek];
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['shift'] = '';
            $hasil[$key]['start'] = '';
            $hasil[$key]['end'] = '';

            $cek_libur = ManajemenHariLibur::where('date', $value->format('Y-m-d'))->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $pengguna)->where('date', $value->format('Y-m-d'))->first();
            $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            $attendance = $presences->where('date', $value->format('Y-m-d'))->first();

            if ($shiftPengguna && $shiftMaster) {
                $hasil[$key]['shift'] = $shiftMaster['code'];
                $hasil[$key]['start'] = minimalisTime($shiftMaster['start_time']);
                $hasil[$key]['end'] = minimalisTime($shiftMaster['end_time']);
            }

            if (!empty($attendance)) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }

                if (isset($shiftMaster['start_time']) && $shiftMaster['end_time']) {

                    if ($attendance->check_in) {
                        $hasil[$key]['check_in'] = $attendance->check_in;
                        $hasil[$key]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $jumlah_pulangcepat++;
                        $hasil[$key]['status'] = "Masuk | Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                    }

                    if ($attendance->check_out) {
                        $hasil[$key]['check_out'] = $attendance->check_out;
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key]['status'] = 'Masuk | Tidak Checkout';
                        $tidak_checkout++;
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = "Masuk | Telat & Tidak Checkout";
                    }
                }
            } else {
                if ($shiftMaster) {
                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else if ($value->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
                // $hasil[$key]['notes'] = $cek_libur->explanation;
            }
        }

        return view('humas/absensi/detail-absensi/view-detail-absensi', compact('auth_data', 'presences', 'start_date', 'end_date', 'dates', 'hasil', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'cek_libur', 'list_unit_kerja', 'nm_pengguna', 'pengguna'));
    }

    //siswa
    public function selectHistoriAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_kelas = Kelas::all();
        // $pengguna = Guru
        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        return view('humas/absensi/detail-absensi-siswa/select-detail-absensi-siswa', compact('auth_data', 'start_date', 'end_date', 'list_kelas'));
    }

    public function actionGetSiswa(Request $request)
    {
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;


        $pengguna = pengguna::where('status_join_table', 3)
            ->with('status_pengguna', 'siswa.kelas')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })
            ->whereHas('siswa.kelas', function ($query) use ($input) {
                $query->where('id_kelas', '=', $input->kelas);
            })
            ->get();

        return $pengguna;
    }

    public function viewHistoriAbsensiSiswa(Request $request, $pengguna = null, $start_date = null, $end_date = null)
    {

        // dd($pengguna);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_kelas = Kelas::all();
        $nm_pengguna = Pengguna::where('id_pengguna', $pengguna)->pluck('nm_pengguna')->first();

        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);

        $presences = PresensiPengguna::where('id_pengguna', $pengguna)->whereBetween('date', [$start_date, $end_date])->get();

        $hasil = [];

        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;


        foreach ($dates as $key => $value) {

            $hasil[$key]['tanggal'] = $value->format('d');
            $hasil[$key]['hari'] = $value->format('l');
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['shift'] = '';
            $hasil[$key]['start'] = '';
            $hasil[$key]['end'] = '';

            $cek_libur = ManajemenHariLibur::where('date', $value->format('Y-m-d'))->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $pengguna)->where('date', $value->format('Y-m-d'))->first();
            $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'] ?? null)->first();
            $attendance = $presences->where('date', $value->format('Y-m-d'))->first();

            if ($shiftPengguna && $shiftMaster) {
                $hasil[$key]['shift'] = $shiftMaster['code'];
                $hasil[$key]['start'] = minimalisTime($shiftMaster['start_time']);
                $hasil[$key]['end'] = minimalisTime($shiftMaster['end_time']);
            }

            if (!empty($attendance)) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }

                if (isset($shiftMaster['start_time']) && $shiftMaster['end_time']) {

                    if ($attendance->check_in) {
                        $hasil[$key]['check_in'] = $attendance->check_in;
                        $hasil[$key]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $jumlah_pulangcepat++;
                        $hasil[$key]['status'] = "Masuk | Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                    }

                    if ($attendance->check_out) {
                        $hasil[$key]['check_out'] = $attendance->check_out;
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key]['status'] = 'Masuk | Tidak Checkout';
                        $tidak_checkout++;
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = "Masuk | Telat & Tidak Checkout";
                    }
                }
            } else {
                if ($shiftMaster) {
                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else if ($value->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
                // $hasil[$key]['notes'] = $cek_libur->explanation;
            }
        }

        return view('humas/absensi/detail-absensi-siswa/view-detail-absensi-siswa', compact('auth_data', 'presences', 'start_date', 'end_date', 'dates', 'hasil', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'cek_libur', 'list_kelas', 'nm_pengguna'));
    }

    public function cetakDetailAbsensi(Request $request, $id_pengguna, $start_date, $end_date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_unit_kerja = UnitKerja::all();
        $nm_pengguna = Pengguna::where('id_pengguna', $id_pengguna)->pluck('nm_pengguna')->first();

        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $dates = CarbonPeriod::create($start_date, $end_date);

        $presences = PresensiPengguna::where('id_pengguna', $id_pengguna)->whereBetween('date', [$start_date, $end_date])->get();

        $hasil = [];

        $hariIndo = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];


        foreach ($dates as $key => $value) {

            $hasil[$key]['tanggal'] = $value->format('Y-m-d');
            $hasil[$key]['hari'] = $hariIndo[$value->dayOfWeek];
            $hasil[$key]['check_in'] = '-';
            $hasil[$key]['check_out'] = '-';
            $hasil[$key]['status'] = '';
            $hasil[$key]['shift'] = '';
            $hasil[$key]['start'] = '';
            $hasil[$key]['end'] = '';

            $cek_libur = ManajemenHariLibur::where('date', $value->format('Y-m-d'))->first();
            $shiftPengguna = ShiftPengguna::where('id_pengguna', $id_pengguna)->where('date', $value->format('Y-m-d'))->first();
            $shiftMaster = ShiftMaster::where('code', $shiftPengguna['id_shift_master'])->first();
            $attendance = $presences->where('date', $value->format('Y-m-d'))->first();

            if ($shiftPengguna && $shiftMaster) {
                $hasil[$key]['nm_pengguna'] = $nm_pengguna;
                $hasil[$key]['shift'] = $shiftMaster['code'];
                $hasil[$key]['start'] = minimalisTime($shiftMaster['start_time']);
                $hasil[$key]['end'] = minimalisTime($shiftMaster['end_time']);
            }

            if (!empty($attendance)) {

                if (isset($shiftMaster['start_time']) && $shiftMaster['end_time']) {

                    if ($attendance->check_in) {
                        $hasil[$key]['check_in'] = $attendance->check_in;
                        $hasil[$key]['status'] = "Masuk";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $hasil[$key]['status'] = "Masuk | Telat";
                    }

                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $hasil[$key]['status'] = "Masuk | Pulang lebih awal";
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['status'] = "Masuk | Telat dan Pulang lebih awal";
                    }

                    if ($attendance->check_out) {
                        $hasil[$key]['check_out'] = $attendance->check_out;
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key]['status'] = 'Masuk | Tidak Checkout';
                    }

                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = "Masuk | Telat & Tidak Checkout";
                    }
                }
            } else {
                if ($shiftMaster) {
                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                    } else if ($value->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Belum Absent';
                    } else {
                        $hasil[$key]['status'] = '';
                    }

                    if ($value->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $cek_libur) { }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
            }
        }

        $products = $hasil;
        return Excel::download(new DetailAbsensi($products), 'detail_absensi_' . $nm_pengguna . '.xlsx');
    }
}
