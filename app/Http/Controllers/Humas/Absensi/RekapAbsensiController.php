<?php

namespace App\Http\Controllers\Humas\Absensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\HistoriAbsensiDay;
use App\Exports\HistoriAbsensiMount;
use App\Models\Kelas;
use App\Models\ManajemenHariLibur;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use App\Models\Sekolah;
use App\Models\ShiftMaster;
use App\Models\ShiftPengguna;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Maatwebsite\Excel\Facades\Excel;

class RekapAbsensiController extends Controller
{
    public function selectRekapAbsensi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_unit_kerja = UnitKerja::all();
        $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');

        return view('humas/absensi/rekap-absensi/select-rekap-absensi', compact('auth_data', 'start_date', 'end_date', 'list_unit_kerja'));
    }

    public function chartAllRekapAbsensi(Request $request, $unit_kerja, $start_date, $end_date)
    {
        set_time_limit(-1);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if (!isset($unit_kerja)) {
            $unit_kerja = "0";
        }
        if ($unit_kerja != "0") {
            if ($unit_kerja == "1") {
                $nm_unit_kerja = 'Pegawai';
                $pengguna = pengguna::where('status_join_table', 1)->where('username', '!=', 'admin')
                    ->with('status_pengguna', 'guru.unit_kerja')
                    ->whereHas('status_pengguna', function ($query) {
                        $query->where('nm_status_pengguna', '=', 'AKTIF');
                    })
                    ->get();
            } else {
                $nm_unit_kerja = UnitKerja::where('id_unit_kerja', $unit_kerja)->first()->nm_unit_kerja;
                $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
                    ->with('status_pengguna', 'guru.unit_kerja')
                    ->whereHas('status_pengguna', function ($query) {
                        $query->where('nm_status_pengguna', '=', 'AKTIF');
                    })
                    ->whereHas('guru.unit_kerja', function ($query) use ($unit_kerja) {
                        $query->where('id_unit_kerja', '=', $unit_kerja);
                    })
                    ->get();
            }
        } else {
            $nm_unit_kerja = 'Semua';
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
                ->with('status_pengguna', 'guru.unit_kerja')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->get();
        }

        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;

        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        // dd($list_pengguna);

        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_shift_master', '!=', 'Siswa')->whereIn('id_pengguna', $list_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->whereIn('status_join_table', [1, 2])->whereIn('id_pengguna', $list_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
            $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                        if ($attendance->status == 'sakit') {
                            $jumlah_sakit++;
                        } elseif ($attendance->status == 'izin') {
                            $jumlah_izin++;
                        }
                    }
                    if ($attendance->check_in) {
                        // $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $jumlah_telat++;
                            $hasil[$key1][$key2]['status'] = "Telat";
                        }
                    }

                    if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                        if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                            $jumlah_pulangcepat++;
                            $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                        }
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                            $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                        }
                    }

                    if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                        $tidak_checkout++;
                    }
                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                            $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                        }
                    }
                } else {
                    if ($shiftMaster) {

                        if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            $hasil[$key1][$key2]['status'] = 'Alpha';
                            $jumlah_alpha++;
                        } else {
                            // $hasil[$key1][$key2]['status'] = '';
                        }
                        if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                            $jumlah_alpha--;
                        }
                    }
                }
                if ($cek_libur) {
                    $hasil[$key1][$key2]['status'] = 'Libur';
                }

                // $hasil[$key1][$key2]['date'] = $date->format('d-m-Y');
                // $hasil[$key1][$key2]['day'] = $date->format('l');
            }
        }

        // if()
        // if ($unit_kerja != '1' ) {
        //     $nm_unit_kerja = UnitKerja::where('id_unit_kerja', $unit_kerja)->first()->nm_unit_kerja;
        // } else {
        //     $nm_unit_kerja = 'Pegawai';
        // }

        // // $hasil;
        // foreach ($hasil as $key => $a) {
        //     $data[$key]['nm_pengguna'] = $a['nm_pengguna'];
        //     $data[$key]['unit_kerja'] = $a['unit_kerja'];
        //     $data[$key]['id_pengguna'] = $a['id_pengguna'];
        //     $data[$key]['masuk'] = isset(array_count_values(array_column($a, 'status'))['Masuk']) ? array_count_values(array_column($a, 'status'))['Masuk'] : '0';
        //     $data[$key]['sakit'] = isset(array_count_values(array_column($a, 'status'))['sakit']) ? array_count_values(array_column($a, 'status'))['sakit'] : '0';
        //     $data[$key]['izin'] = isset(array_count_values(array_column($a, 'status'))['izin']) ? array_count_values(array_column($a, 'status'))['izin'] : '0';
        //     $data[$key]['telat'] = isset(array_count_values(array_column($a, 'status'))['Telat']) ? array_count_values(array_column($a, 'status'))['Telat'] : '0';
        //     $data[$key]['pulang'] = isset(array_count_values(array_column($a, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Pulang lebih awal'] : '0';
        //     $data[$key]['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal'] : '0';
        //     $data[$key]['tidakCheckout'] = isset(array_count_values(array_column($a, 'status'))['Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Tidak Checkout'] : '0';
        //     $data[$key]['Telat & Tidak Checkout'] = isset(array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout'] : '0';
        //     // $data[$key]['kosong'] = isset(array_count_values(array_column($a, 'status'))['']) ? array_count_values(array_column($a, 'status'))[''] : '0';
        //     $data[$key]['alpha'] = isset(array_count_values(array_column($a, 'status'))['Alpha']) ? array_count_values(array_column($a, 'status'))['Alpha'] : '0';
        //     // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        //     // dd(array_count_values(array_column($a, 'date')));

        // }

        // $start_date = Carbon::parse($start_date)->format('Y-m-d');
        // $end_date = Carbon::parse($end_date)->format('Y-m-d');

        return view('humas/absensi/rekap-absensi/chart-rekap-semua-absensi', compact('auth_data', 'nm_unit_kerja', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'unit_kerja', 'start_date', 'end_date'));
    }

    public function viewRekapAbsensi(Request $request, $unit_kerja, $start_date, $end_date)
    {
        set_time_limit(-1);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if (!isset($unit_kerja)) {
            $unit_kerja = "0";
        }
        if ($unit_kerja != "0") {
            if ($unit_kerja == "1") {
                $pengguna = pengguna::where('status_join_table', 1)->where('username', '!=', 'admin')
                    ->with('status_pengguna', 'guru.unit_kerja')
                    ->whereHas('status_pengguna', function ($query) {
                        $query->where('nm_status_pengguna', '=', 'AKTIF');
                    })
                    ->get();
            } else {
                $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
                    ->with('status_pengguna', 'guru.unit_kerja')
                    ->whereHas('status_pengguna', function ($query) {
                        $query->where('nm_status_pengguna', '=', 'AKTIF');
                    })
                    ->whereHas('guru.unit_kerja', function ($query) use ($unit_kerja) {
                        $query->where('id_unit_kerja', '=', $unit_kerja);
                    })
                    ->get();
            }
        } else {
            $pengguna = pengguna::whereIn('status_join_table', [1, 2])->where('username', '!=', 'admin')
                ->with('status_pengguna', 'guru.unit_kerja')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->get();
        }

        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;

        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        // dd($list_pengguna);

        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_shift_master', '!=', 'Siswa')->whereIn('id_pengguna', $list_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->whereIn('status_join_table', [1, 2])->whereIn('id_pengguna', $list_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
            $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                        if ($attendance->status == 'sakit') {
                            $jumlah_sakit++;
                        } elseif ($attendance->status == 'izin') {
                            $jumlah_izin++;
                        }
                    }
                    if ($attendance->check_in) {
                        // $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $jumlah_telat++;
                            $hasil[$key1][$key2]['status'] = "Telat";
                        }
                    }

                    if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                        if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                            $jumlah_pulangcepat++;
                            $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                        }
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                            $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                        }
                    }

                    if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                        $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                        $tidak_checkout++;
                    }
                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                            $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                        }
                    }
                } else {
                    if ($shiftMaster) {

                        if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            $hasil[$key1][$key2]['status'] = 'Alpha';
                            $jumlah_alpha++;
                        } else {
                            // $hasil[$key1][$key2]['status'] = '';
                        }
                        if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                            $jumlah_alpha--;
                        }
                    }
                }
                if ($cek_libur) {
                    $hasil[$key1][$key2]['status'] = 'Libur';
                }

                // $hasil[$key1][$key2]['date'] = $date->format('d-m-Y');
                // $hasil[$key1][$key2]['day'] = $date->format('l');
            }
        }
        $list_unit_kerja = UnitKerja::all();
        // $hasil;
        foreach ($hasil as $key => $a) {
            $data[$key]['nm_pengguna'] = $a['nm_pengguna'];
            $data[$key]['unit_kerja'] = $a['unit_kerja'];
            $data[$key]['id_pengguna'] = $a['id_pengguna'];
            $data[$key]['masuk'] = isset(array_count_values(array_column($a, 'status'))['Masuk']) ? array_count_values(array_column($a, 'status'))['Masuk'] : '0';
            $data[$key]['sakit'] = isset(array_count_values(array_column($a, 'status'))['sakit']) ? array_count_values(array_column($a, 'status'))['sakit'] : '0';
            $data[$key]['izin'] = isset(array_count_values(array_column($a, 'status'))['izin']) ? array_count_values(array_column($a, 'status'))['izin'] : '0';
            $data[$key]['telat'] = isset(array_count_values(array_column($a, 'status'))['Telat']) ? array_count_values(array_column($a, 'status'))['Telat'] : '0';
            $data[$key]['pulang'] = isset(array_count_values(array_column($a, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Pulang lebih awal'] : '0';
            $data[$key]['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal'] : '0';
            $data[$key]['tidakCheckout'] = isset(array_count_values(array_column($a, 'status'))['Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Tidak Checkout'] : '0';
            $data[$key]['Telat & Tidak Checkout'] = isset(array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout'] : '0';
            // $data[$key]['kosong'] = isset(array_count_values(array_column($a, 'status'))['']) ? array_count_values(array_column($a, 'status'))[''] : '0';
            $data[$key]['alpha'] = isset(array_count_values(array_column($a, 'status'))['Alpha']) ? array_count_values(array_column($a, 'status'))['Alpha'] : '0';
            // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
            // dd(array_count_values(array_column($a, 'date')));

        }


        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');

        // dd($hasil);
        return view('humas/absensi/rekap-absensi/view-rekap-absensi', compact('auth_data', 'list_unit_kerja', 'data', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'unit_kerja', 'start_date', 'end_date'));
    }

    public function selectRekapAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_kelas = Kelas::all();
        $start_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');

        return view('humas/absensi/rekap-absensi-siswa/select-rekap-absensi-siswa', compact('auth_data', 'start_date', 'end_date', 'list_kelas'));
    }

    public function chartAllRekapAbsensiSiswa(Request $request, $id_kelas, $start_date, $end_date)
    {
        set_time_limit(-1);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9, 10, 11, 12]);
                })->get()->sortBy('siswa.kelas.nm_kelas')->sortBy('siswa.kelas.tingkat');
            $nm_kelas = 'Semua';
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->orderBy('nm_pengguna', 'asc')->get();
            $nm_kelas = Kelas::where('id_kelas', $id_kelas)->first()->nm_kelas;
        }

        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        // $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        // $tidak_checkout = 0;
        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_shift_master', 'Siswa')->whereIn('id_pengguna', $list_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('status_join_table', 3)->whereIn('id_pengguna', $list_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['nm_pengguna'] =  $value->nm_pengguna;
            // $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';
            $hasil[$key1]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key1]['nis'] = $value->username;
            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                        if ($attendance->status == 'sakit') {
                            $jumlah_sakit++;
                        } elseif ($attendance->status == 'izin') {
                            $jumlah_izin++;
                        }
                    }
                    if ($attendance->check_in) {
                        // $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $jumlah_telat++;
                            $hasil[$key1][$key2]['status'] = "Telat";
                        }
                    }

                    // if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                    //     if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                    //         // $jumlah_pulangcepat++;
                    //         $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                    //     }
                    // }

                    // if (isset($shiftMaster['start_time'])) {
                    //     if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                    //         $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                    //     }
                    // // }

                    // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    //     $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                    //     // $tidak_checkout++;
                    // }
                    // if (isset($shiftMaster['start_time'])) {
                    //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                    //         $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                    //     }
                    // }
                } else {
                    if ($shiftMaster) {

                        if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            $hasil[$key1][$key2]['status'] = 'Alpha';
                            $jumlah_alpha++;
                        } else {
                            // $hasil[$key1][$key2]['status'] = '';
                        }
                        if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                            $jumlah_alpha--;
                        }
                    }
                }
                if ($cek_libur) {
                    $hasil[$key1][$key2]['status'] = 'Libur';
                }

                // $hasil[$key1][$key2]['date'] = $date->format('d-m-Y');
            }
        }
        // $list_kelas = Kelas::all();
        // $hasil;
        // foreach ($hasil as $key => $a) {
        //     $data[$key]['nm_pengguna'] = $a['nm_pengguna'];
        //     $data[$key]['kelas'] = $a['kelas'];
        //     $data[$key]['id_pengguna'] = $a['id_pengguna'];
        //     $data[$key]['masuk'] = isset(array_count_values(array_column($a, 'status'))['Masuk']) ? array_count_values(array_column($a, 'status'))['Masuk'] : '0';
        //     $data[$key]['sakit'] = isset(array_count_values(array_column($a, 'status'))['sakit']) ? array_count_values(array_column($a, 'status'))['sakit'] : '0';
        //     $data[$key]['izin'] = isset(array_count_values(array_column($a, 'status'))['izin']) ? array_count_values(array_column($a, 'status'))['izin'] : '0';
        //     $data[$key]['telat'] = isset(array_count_values(array_column($a, 'status'))['Telat']) ? array_count_values(array_column($a, 'status'))['Telat'] : '0';
        //     // $data[$key]['pulang'] = isset(array_count_values(array_column($a, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Pulang lebih awal'] : '0';
        //     // $data[$key]['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal'] : '0';
        //     // $data[$key]['tidakCheckout'] = isset(array_count_values(array_column($a, 'status'))['Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Tidak Checkout'] : '0';
        //     // $data[$key]['Telat & Tidak Checkout'] = isset(array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout'] : '0';
        //     // $data[$key]['kosong'] = isset(array_count_values(array_column($a, 'status'))['']) ? array_count_values(array_column($a, 'status'))[''] : '0';
        //     $data[$key]['alpha'] = isset(array_count_values(array_column($a, 'status'))['Alpha']) ? array_count_values(array_column($a, 'status'))['Alpha'] : '0';
        //     // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        // }

        // $start_date = Carbon::parse($start_date)->format('Y-m-d');
        // $end_date = Carbon::parse($end_date)->format('Y-m-d');

        return view('humas/absensi/rekap-absensi-siswa/chart-rekap-semua-siswa', compact('auth_data', 'nm_kelas', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat',  'jumlah_alpha', 'start_date', 'end_date', 'id_kelas'));
    }

    public function viewRekapAbsensiSiswa(Request $request, $id_kelas, $start_date, $end_date)
    {
        set_time_limit(-1);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($id_kelas == "1") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9]);
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "2") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [10, 11, 12]);
                })->get()->sortBy('siswa.kelas.nm_kelas');
        } elseif ($id_kelas == "0") {
            $pengguna = Pengguna::with('status_pengguna', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('siswa.kelas', function ($query) {
                    $query->whereIn('tingkat',  [7, 8, 9, 10, 11, 12]);
                })->get()->sortBy('siswa.kelas.nm_kelas')->sortBy('siswa.kelas.tingkat');
        } else {
            $pengguna = Pengguna::with('status_pengguna', 'siswa', 'siswa.kelas')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->whereHas('siswa', function ($query) use ($id_kelas) {
                    $query->where('id_kelas', '=', $id_kelas);
                })->orderBy('nm_pengguna', 'asc')->get();
        }

        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        // $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        // $tidak_checkout = 0;
        $list_pengguna = $pengguna->pluck('id_pengguna')->toArray();
        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_shift_master', 'Siswa')->whereIn('id_pengguna', $list_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('status_join_table', 3)->whereIn('id_pengguna', $list_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();

        foreach ($pengguna as $key1 => $value) {
            $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            $hasil[$key1]['nm_pengguna'] =  $value->nm_pengguna;
            // $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';
            $hasil[$key1]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            $hasil[$key1]['nis'] = $value->username;
            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

                if ($attendance) {

                    if ($attendance->status) {
                        $hasil[$key1][$key2]['status'] = $attendance->status;
                        if ($attendance->status == 'sakit') {
                            $jumlah_sakit++;
                        } elseif ($attendance->status == 'izin') {
                            $jumlah_izin++;
                        }
                    }
                    if ($attendance->check_in) {
                        // $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        $hasil[$key1][$key2]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $jumlah_telat++;
                            $hasil[$key1][$key2]['status'] = "Telat";
                        }
                    }

                    // if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                    //     if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                    //         // $jumlah_pulangcepat++;
                    //         $hasil[$key1][$key2]['status'] = "Pulang lebih awal";
                    //     }
                    // }

                    // if (isset($shiftMaster['start_time'])) {
                    //     if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                    //         $hasil[$key1][$key2]['status'] = "Telat dan Pulang lebih awal";
                    //     }
                    // // }

                    // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    //     $hasil[$key1][$key2]['status'] = 'Tidak Checkout';
                    //     // $tidak_checkout++;
                    // }
                    // if (isset($shiftMaster['start_time'])) {
                    //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                    //         $hasil[$key1][$key2]['status'] = "Telat & Tidak Checkout";
                    //     }
                    // }
                } else {
                    if ($shiftMaster) {

                        if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            $hasil[$key1][$key2]['status'] = 'Alpha';
                            $jumlah_alpha++;
                        } else {
                            // $hasil[$key1][$key2]['status'] = '';
                        }
                        if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                            $jumlah_alpha--;
                        }
                    }
                }
                if ($cek_libur) {
                    $hasil[$key1][$key2]['status'] = 'Libur';
                }

                // $hasil[$key1][$key2]['date'] = $date->format('d-m-Y');
            }
        }
        $list_kelas = Kelas::all();
        // $hasil;
        foreach ($hasil as $key => $a) {
            $data[$key]['nm_pengguna'] = $a['nm_pengguna'];
            $data[$key]['kelas'] = $a['kelas'];
            $data[$key]['id_pengguna'] = $a['id_pengguna'];
            $data[$key]['masuk'] = isset(array_count_values(array_column($a, 'status'))['Masuk']) ? array_count_values(array_column($a, 'status'))['Masuk'] : '0';
            $data[$key]['sakit'] = isset(array_count_values(array_column($a, 'status'))['sakit']) ? array_count_values(array_column($a, 'status'))['sakit'] : '0';
            $data[$key]['izin'] = isset(array_count_values(array_column($a, 'status'))['izin']) ? array_count_values(array_column($a, 'status'))['izin'] : '0';
            $data[$key]['telat'] = isset(array_count_values(array_column($a, 'status'))['Telat']) ? array_count_values(array_column($a, 'status'))['Telat'] : '0';
            // $data[$key]['pulang'] = isset(array_count_values(array_column($a, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Pulang lebih awal'] : '0';
            // $data[$key]['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($a, 'status'))['Telat dan Pulang lebih awal'] : '0';
            // $data[$key]['tidakCheckout'] = isset(array_count_values(array_column($a, 'status'))['Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Tidak Checkout'] : '0';
            // $data[$key]['Telat & Tidak Checkout'] = isset(array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($a, 'status'))['Telat & Tidak Checkout'] : '0';
            // $data[$key]['kosong'] = isset(array_count_values(array_column($a, 'status'))['']) ? array_count_values(array_column($a, 'status'))[''] : '0';
            $data[$key]['alpha'] = isset(array_count_values(array_column($a, 'status'))['Alpha']) ? array_count_values(array_column($a, 'status'))['Alpha'] : '0';
            // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        }

        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');

        return view('humas/absensi/rekap-absensi-siswa/view-rekap-absensi-siswa', compact('auth_data', 'list_kelas', 'data', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat',  'jumlah_alpha', 'start_date', 'end_date', 'id_kelas'));
    }


    public function cetakRekapAbsensi(Request $request, $id_pengguna, $start_date, $end_date)
    {

        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->with('status_pengguna', 'guru.unit_kerja')->first();

        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();
        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;
        // foreach ($pengguna as $key1 => $value) {
        //     $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
        //     $hasil[$key1]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
        //     $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

        foreach ($dates as $key => $date) {
            $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
            $hasil[$key]['status'] = '';
            $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

            if ($attendance) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }
                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = Carbon::parse($attendance->check_in)->format('H:i');
                    $hasil[$key]['status'] = "Masuk";

                    $jumlah_hadir++;
                }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = Carbon::parse($attendance->check_out)->format('H:i');
                }
                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Telat";
                    }
                }

                if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $jumlah_pulangcepat++;
                        $hasil[$key]['status'] = "Pulang lebih awal";
                    }
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['status'] = "Telat dan Pulang lebih awal";
                    }
                }

                if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    $hasil[$key]['status'] = 'Tidak Checkout';
                    $tidak_checkout++;
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                        $hasil[$key]['status'] = "Telat & Tidak Checkout";
                    }
                }
            } else {
                if ($shiftMaster) {

                    if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else {
                        // $hasil[$key]['status'] = '';
                    }
                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
            }

            $hasil[$key]['date'] = $date->format('d-m-Y');
            // setlocale(LC_TIME, 'id_ID');translatedFormat
            // Carbon::setLocale('id');
            $hariIndo = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
            $hasil[$key]['day'] = $hariIndo[$date->dayOfWeek];
            // dd($hasil[$key]['day']);
        }


        // $list_unit_kerja = UnitKerja::all();
        // $hasil;
        // foreach ($hasil as $key => $a) {
        // $data['nm_pengguna'] = $hasil['nm_pengguna'];
        // $data['unit_kerja'] = $hasil['unit_kerja'];
        // $data['id_pengguna'] = $hasil['id_pengguna'];
        $data['masuk'] = isset(array_count_values(array_column($hasil, 'status'))['Masuk']) ? array_count_values(array_column($hasil, 'status'))['Masuk'] : '0';
        $data['sakit'] = isset(array_count_values(array_column($hasil, 'status'))['sakit']) ? array_count_values(array_column($hasil, 'status'))['sakit'] : '0';
        $data['izin'] = isset(array_count_values(array_column($hasil, 'status'))['izin']) ? array_count_values(array_column($hasil, 'status'))['izin'] : '0';
        $data['telat'] = isset(array_count_values(array_column($hasil, 'status'))['Telat']) ? array_count_values(array_column($hasil, 'status'))['Telat'] : '0';
        $data['pulang'] = isset(array_count_values(array_column($hasil, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Pulang lebih awal'] : '0';
        $data['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal'] : '0';
        $data['tidakCheckout'] = isset(array_count_values(array_column($hasil, 'status'))['Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Tidak Checkout'] : '0';
        $data['Telat & Tidak Checkout'] = isset(array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout'] : '0';
        // $data['kosong'] = isset(array_count_values(array_column($hasil, 'status'))['']) ? array_count_values(array_column($hasil, 'status'))[''] : '0';
        $data['alpha'] = isset(array_count_values(array_column($hasil, 'status'))['Alpha']) ? array_count_values(array_column($hasil, 'status'))['Alpha'] : '0';
        // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        // dd(array_count_values(array_column($a, 'date')));

        // }

        // dd($data);
        return view('humas/absensi/rekap-absensi/cetak-rekap-absensi', compact('auth_data', 'hasil', 'data', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'start_date', 'end_date', 'pengguna'));
    }

    public function chartRekapAbsensi(Request $request, $id_pengguna, $start_date, $end_date)
    {

        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->with('status_pengguna', 'guru.unit_kerja')->first();

        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();
        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        $tidak_checkout = 0;
        // foreach ($pengguna as $key1 => $value) {
        //     $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
        //     $hasil[$key1]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
        //     $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

        foreach ($dates as $key => $date) {
            $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
            $hasil[$key]['status'] = '';
            $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

            if ($attendance) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }
                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = Carbon::parse($attendance->check_in)->format('H:i');
                    $hasil[$key]['status'] = "Masuk";

                    $jumlah_hadir++;
                }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = Carbon::parse($attendance->check_out)->format('H:i');
                }
                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Telat";
                    }
                }

                if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                    if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                        $jumlah_pulangcepat++;
                        $hasil[$key]['status'] = "Pulang lebih awal";
                    }
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                        $hasil[$key]['status'] = "Telat dan Pulang lebih awal";
                    }
                }

                if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                    $hasil[$key]['status'] = 'Tidak Checkout';
                    $tidak_checkout++;
                }
                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                        $hasil[$key]['status'] = "Telat & Tidak Checkout";
                    }
                }
            } else {
                if ($shiftMaster) {

                    if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else {
                        // $hasil[$key]['status'] = '';
                    }
                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
            }

            $hasil[$key]['date'] = $date->format('d-m-Y');
            // setlocale(LC_TIME, 'id_ID');translatedFormat
            // Carbon::setLocale('id');
            $hariIndo = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
            $hasil[$key]['day'] = $hariIndo[$date->dayOfWeek];
            // dd($hasil[$key]['day']);
        }


        // $list_unit_kerja = UnitKerja::all();
        // $hasil;
        // foreach ($hasil as $key => $a) {
        // $data['nm_pengguna'] = $hasil['nm_pengguna'];
        // $data['unit_kerja'] = $hasil['unit_kerja'];
        // $data['id_pengguna'] = $hasil['id_pengguna'];
        $data['masuk'] = isset(array_count_values(array_column($hasil, 'status'))['Masuk']) ? array_count_values(array_column($hasil, 'status'))['Masuk'] : '0';
        $data['sakit'] = isset(array_count_values(array_column($hasil, 'status'))['sakit']) ? array_count_values(array_column($hasil, 'status'))['sakit'] : '0';
        $data['izin'] = isset(array_count_values(array_column($hasil, 'status'))['izin']) ? array_count_values(array_column($hasil, 'status'))['izin'] : '0';
        $data['telat'] = isset(array_count_values(array_column($hasil, 'status'))['Telat']) ? array_count_values(array_column($hasil, 'status'))['Telat'] : '0';
        $data['pulang'] = isset(array_count_values(array_column($hasil, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Pulang lebih awal'] : '0';
        $data['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal'] : '0';
        $data['tidakCheckout'] = isset(array_count_values(array_column($hasil, 'status'))['Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Tidak Checkout'] : '0';
        $data['Telat & Tidak Checkout'] = isset(array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout'] : '0';
        // $data['kosong'] = isset(array_count_values(array_column($hasil, 'status'))['']) ? array_count_values(array_column($hasil, 'status'))[''] : '0';
        $data['alpha'] = isset(array_count_values(array_column($hasil, 'status'))['Alpha']) ? array_count_values(array_column($hasil, 'status'))['Alpha'] : '0';
        // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        // dd(array_count_values(array_column($a, 'date')));

        // }

        // dd($data);
        return view('humas/absensi/rekap-absensi/chart-rekap-absensi', compact('auth_data', 'hasil', 'data', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_pulangcepat', 'jumlah_alpha', 'tidak_checkout', 'start_date', 'end_date', 'pengguna'));
    }

    public function chartRekapAbsensiSiswa(Request $request, $id_pengguna, $start_date, $end_date)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->with('status_pengguna', 'siswa.kelas')->first();

        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();
        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        // $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        // $tidak_checkout = 0;
        // foreach ($pengguna as $key1 => $value) {
        //     $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
        //     $hasil[$key1]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
        //     $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

        foreach ($dates as $key => $date) {
            $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
            $hasil[$key]['status'] = '';
            $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

            if ($attendance) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }
                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = Carbon::parse($attendance->check_in)->format('H:i');
                    $hasil[$key]['status'] = "Masuk";

                    $jumlah_hadir++;
                }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = Carbon::parse($attendance->check_out)->format('H:i');
                }
                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Telat";
                    }
                }

                // if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                //     if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                //         // $jumlah_pulangcepat++;
                //         $hasil[$key]['status'] = "Pulang lebih awal";
                //     }
                // }

                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                //         $hasil[$key]['status'] = "Telat dan Pulang lebih awal";
                //     }
                // }

                // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                //     $hasil[$key]['status'] = 'Tidak Checkout';
                //     $tidak_checkout++;
                // }
                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                //         $hasil[$key]['status'] = "Telat & Tidak Checkout";
                //     }
                // }
            } else {
                if ($shiftMaster) {

                    if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else {
                        // $hasil[$key]['status'] = '';
                    }
                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
            }

            $hasil[$key]['date'] = $date->format('d-m-Y');
            // setlocale(LC_TIME, 'id_ID');translatedFormat
            // Carbon::setLocale('id');
            $hariIndo = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
            $hasil[$key]['day'] = $hariIndo[$date->dayOfWeek];
            // dd($hasil[$key]['day']);
        }


        // $list_unit_kerja = UnitKerja::all();
        // $hasil;
        // foreach ($hasil as $key => $a) {
        // $data['nm_pengguna'] = $hasil['nm_pengguna'];
        // $data['unit_kerja'] = $hasil['unit_kerja'];
        // $data['id_pengguna'] = $hasil['id_pengguna'];
        $data['masuk'] = isset(array_count_values(array_column($hasil, 'status'))['Masuk']) ? array_count_values(array_column($hasil, 'status'))['Masuk'] : '0';
        $data['sakit'] = isset(array_count_values(array_column($hasil, 'status'))['sakit']) ? array_count_values(array_column($hasil, 'status'))['sakit'] : '0';
        $data['izin'] = isset(array_count_values(array_column($hasil, 'status'))['izin']) ? array_count_values(array_column($hasil, 'status'))['izin'] : '0';
        $data['telat'] = isset(array_count_values(array_column($hasil, 'status'))['Telat']) ? array_count_values(array_column($hasil, 'status'))['Telat'] : '0';
        // $data['pulang'] = isset(array_count_values(array_column($hasil, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Pulang lebih awal'] : '0';
        // $data['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal'] : '0';
        // $data['tidakCheckout'] = isset(array_count_values(array_column($hasil, 'status'))['Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Tidak Checkout'] : '0';
        // $data['Telat & Tidak Checkout'] = isset(array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout'] : '0';
        // $data['kosong'] = isset(array_count_values(array_column($hasil, 'status'))['']) ? array_count_values(array_column($hasil, 'status'))[''] : '0';
        $data['alpha'] = isset(array_count_values(array_column($hasil, 'status'))['Alpha']) ? array_count_values(array_column($hasil, 'status'))['Alpha'] : '0';
        // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        // dd(array_count_values(array_column($a, 'date')));

        // }

        return view('humas/absensi/rekap-absensi-siswa/chart-rekap-absensi-siswa', compact('auth_data', 'hasil', 'data', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_alpha',  'start_date', 'end_date', 'pengguna'));
    }

    public function cetakRekapAbsensiSiswa(Request $request, $id_pengguna, $start_date, $end_date)
    {

        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->with('status_pengguna', 'siswa.kelas')->first();

        $allShiftPengguna = ShiftPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->with('shift_master')->get();
        $allPresensiPengguna = PresensiPengguna::whereBetween('date', [$start_date, $end_date])->where('id_pengguna', $id_pengguna)->get();
        $dates = CarbonPeriod::create($start_date, $end_date);
        $libur = ManajemenHariLibur::whereBetween('date', [$start_date, $end_date])->get();
        $hasil = [];
        $jumlah_hadir = 0;
        $jumlah_sakit = 0;
        $jumlah_izin = 0;
        $jumlah_telat = 0;
        // $jumlah_pulangcepat = 0;
        $jumlah_alpha = 0;
        // $tidak_checkout = 0;
        // foreach ($pengguna as $key1 => $value) {
        //     $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
        //     $hasil[$key1]['nm_pengguna'] = $value->gelar_depan . ' ' . $value->nm_pengguna . ' ' . $value->gelar_belakang;
        //     $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';

        foreach ($dates as $key => $date) {
            $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
            $hasil[$key]['status'] = '';
            $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $id_pengguna)->first();
            $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

            if ($attendance) {

                if ($attendance->status) {
                    $hasil[$key]['status'] = $attendance->status;
                    if ($attendance->status == 'sakit') {
                        $jumlah_sakit++;
                    } elseif ($attendance->status == 'izin') {
                        $jumlah_izin++;
                    }
                }
                if ($attendance->check_in) {
                    $hasil[$key]['check_in'] = Carbon::parse($attendance->check_in)->format('H:i');
                    $hasil[$key]['status'] = "Masuk";

                    $jumlah_hadir++;
                }
                if ($attendance->check_out) {
                    $hasil[$key]['check_out'] = Carbon::parse($attendance->check_out)->format('H:i');
                }
                if ($attendance->notes) {
                    $hasil[$key]['notes'] = $attendance->notes;
                }

                if (isset($shiftMaster['start_time'])) {
                    if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                        $jumlah_telat++;
                        $hasil[$key]['status'] = "Telat";
                    }
                }

                // if (isset($shiftMaster['end_time']) && isset($attendance->check_out)) {
                //     if ($attendance->check_out < $shiftMaster['end_time'] && $attendance->check_out > $attendance->check_in) {
                //         // $jumlah_pulangcepat++;
                //         $hasil[$key]['status'] = "Pulang lebih awal";
                //     }
                // }

                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && !$attendance->check_out == null  && $attendance->check_in > $shiftMaster['start_time'] && $attendance->check_out < $shiftMaster['end_time']) {
                //         $hasil[$key]['status'] = "Telat dan Pulang lebih awal";
                //     }
                // }

                // if ($date < Carbon::now()->format('Y-m-d') && $attendance->check_in && !$attendance->check_out) {
                //     $hasil[$key]['status'] = 'Tidak Checkout';
                //     $tidak_checkout++;
                // }
                // if (isset($shiftMaster['start_time'])) {
                //     if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time'] && !$attendance->check_out && $date < Carbon::now()->format('Y-m-d')) {

                //         $hasil[$key]['status'] = "Telat & Tidak Checkout";
                //     }
                // }
            } else {
                if ($shiftMaster) {

                    if ($date->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                        $hasil[$key]['status'] = 'Alpha';
                        $jumlah_alpha++;
                    } else {
                        // $hasil[$key]['status'] = '';
                    }
                    if ($date < Carbon::now()->format('Y-m-d') && $cek_libur) {
                        $jumlah_alpha--;
                    }
                }
            }
            if ($cek_libur) {
                $hasil[$key]['status'] = 'Libur';
            }

            $hasil[$key]['date'] = $date->format('d-m-Y');
            // setlocale(LC_TIME, 'id_ID');translatedFormat
            // Carbon::setLocale('id');
            $hariIndo = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
            $hasil[$key]['day'] = $hariIndo[$date->dayOfWeek];
            // dd($hasil[$key]['day']);
        }


        // $list_unit_kerja = UnitKerja::all();
        // $hasil;
        // foreach ($hasil as $key => $a) {
        // $data['nm_pengguna'] = $hasil['nm_pengguna'];
        // $data['unit_kerja'] = $hasil['unit_kerja'];
        // $data['id_pengguna'] = $hasil['id_pengguna'];
        $data['masuk'] = isset(array_count_values(array_column($hasil, 'status'))['Masuk']) ? array_count_values(array_column($hasil, 'status'))['Masuk'] : '0';
        $data['sakit'] = isset(array_count_values(array_column($hasil, 'status'))['sakit']) ? array_count_values(array_column($hasil, 'status'))['sakit'] : '0';
        $data['izin'] = isset(array_count_values(array_column($hasil, 'status'))['izin']) ? array_count_values(array_column($hasil, 'status'))['izin'] : '0';
        $data['telat'] = isset(array_count_values(array_column($hasil, 'status'))['Telat']) ? array_count_values(array_column($hasil, 'status'))['Telat'] : '0';
        // $data['pulang'] = isset(array_count_values(array_column($hasil, 'status'))['Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Pulang lebih awal'] : '0';
        // $data['telatDanPulangLebihAwal'] = isset(array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal']) ? array_count_values(array_column($hasil, 'status'))['Telat dan Pulang lebih awal'] : '0';
        // $data['tidakCheckout'] = isset(array_count_values(array_column($hasil, 'status'))['Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Tidak Checkout'] : '0';
        // $data['Telat & Tidak Checkout'] = isset(array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout']) ? array_count_values(array_column($hasil, 'status'))['Telat & Tidak Checkout'] : '0';
        // $data['kosong'] = isset(array_count_values(array_column($hasil, 'status'))['']) ? array_count_values(array_column($hasil, 'status'))[''] : '0';
        $data['alpha'] = isset(array_count_values(array_column($hasil, 'status'))['Alpha']) ? array_count_values(array_column($hasil, 'status'))['Alpha'] : '0';
        // $data[$key]['libur'] = isset(array_count_values(array_column($a, 'status'))['Libur']) ? array_count_values(array_column($a, 'status'))['Libur'] : '0';
        // dd(array_count_values(array_column($a, 'date')));

        // }

        // dd($data);
        return view('humas/absensi/rekap-absensi-siswa/cetak-rekap-absensi-siswa', compact('auth_data', 'hasil', 'data', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat', 'jumlah_alpha',  'start_date', 'end_date', 'pengguna'));
    }
}
