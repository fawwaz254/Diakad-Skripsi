<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


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

class RekapAbsensiSiswa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $input;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(-1);
        $pengguna = $this->input->pengguna;
        $start_date = $this->input->start_date;
        $end_date = $this->input->end_date;
        $nm_kelas = $this->input->nm_kelas;
        $auth_data = $this->input->auth_data;

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
            // $hasil[$key1]['id_pengguna'] = $value->id_pengguna;
            // $hasil[$key1]['nm_pengguna'] =  $value->nm_pengguna;
            // $hasil[$key1]['unit_kerja'] = isset($value->guru->unit_kerja)  ?  $value->guru->unit_kerja->nm_unit_kerja : 'Pegawai';
            // $hasil[$key1]['kelas'] = isset($value->siswa->kelas->nm_kelas) ? $value->siswa->kelas->nm_kelas : '-';
            // $hasil[$key1]['nis'] = $value->username;
            foreach ($dates as $key2 => $date) {
                $cek_libur = $libur->firstWhere('date', $date->format('Y-m-d'));
                // $hasil[$key1][$key2]['status'] = '';
                $shiftPengguna = $allShiftPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $attendance =  $allPresensiPengguna->where('date', $date->format('Y-m-d'))->where('id_pengguna', '=', $value->id_pengguna)->first();
                $shiftMaster = isset($shiftPengguna->shift_master) ? $shiftPengguna->shift_master : null;

                if ($attendance) {

                    if ($attendance->status) {
                        // $hasil[$key1][$key2]['status'] = $attendance->status;
                        if ($attendance->status == 'sakit') {
                            $jumlah_sakit++;
                        } elseif ($attendance->status == 'izin') {
                            $jumlah_izin++;
                        }
                    }
                    if ($attendance->check_in) {
                        // $hasil[$key1][$key2]['check_in'] = $attendance->check_in;
                        // $hasil[$key1][$key2]['status'] = "Masuk";
                        $jumlah_hadir++;
                    }

                    if (isset($shiftMaster['start_time'])) {
                        if (!$shiftMaster['start_time'] == null && $attendance->check_in > $shiftMaster['start_time']) {
                            $jumlah_telat++;
                            // $hasil[$key1][$key2]['status'] = "Telat";
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
                            // $hasil[$key1][$key2]['status'] = 'Alpha';
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
                    // $hasil[$key1][$key2]['status'] = 'Libur';
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

        return view('humas/absensi/rekap-absensi-siswa/chart-rekap-semua-siswa', compact('auth_data', 'nm_kelas', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_telat',  'jumlah_alpha', 'start_date', 'end_date'));
    }
}
