<?php

namespace App\Http\Controllers\Guru\RewardSiswa;

use Session;
use Carbon\Carbon;

use App\Models\Kelas;

use App\Models\Siswa;
use Carbon\CarbonPeriod;
use App\Models\WaliMurid;
use App\Models\JawabanForm;
use App\Models\RewardSiswa;
use App\Libraries\LibGlobal;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\AktivitasRewardSiswa;
use App\Models\JenisAktivitasReward;
use Illuminate\Support\Facades\Auth;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\PengisianKegiatanHarian;
use App\Models\PresensiMp as PresensiMp;
// use Auth;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Pendidikan\LibDataAkademik;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Models\PresensiMpPelanggaran as PresensiMpPelanggaran;
// use Validator;

class InputRewardSiswaController extends BaseController
{
    public function viewInputRewardSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date_input = '';
        $pengisian_kegiatan_harian = false;

        $now = Carbon::now();

        if ($request->segment(3) == "input-reward-harian") {
            $jenis = 1;
            $date_input = 'tanggal ' . Carbon::now('Asia/Jakarta')->isoFormat('D MMM Y');
        } else if ($request->segment(3) == "input-reward-mingguan") {
            $jenis = 2;

            $date_input = 'minggu ini tanggal (' . Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('D MMM Y') . ' - ' . Carbon::now('Asia/Jakarta')->endOfWeek()->isoFormat('D MMM Y') . ')';
        } else if ($request->segment(3) == "input-reward-bulanan") {
            $jenis = 3;

            $date_input = 'bulan ' . Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('MMMM');
        } else if ($request->segment(3) == "input-reward-insidentil") {
            $jenis = 4;

            $date_input = 'tanggal ' . Carbon::now('Asia/Jakarta')->isoFormat('D MMM Y');
        }

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('guru/reward-siswa/input-reward-siswa/view-input-reward-siswa', compact('auth_data', 'data_kelas', 'jenis', 'date_input'));
    }

    public function ajaxGetAktivitasReward(Request $request)
    {
        $date_now = Carbon::now('Asia/Jakarta');
        $id_jenis_aktivitas_reward = $request->input('jenis_aktivitas');
        $id_kelas = $request->input('id_kelas');

        $data_reward_siswa = RewardSiswa::where('id_kelas', $id_kelas)->whereDate('created_at', $date_now->format('Y-m-d'))->get();
        $data_aktivitas_reward = AktivitasRewardSiswa::where('id_jenis_aktivitas_reward', $id_jenis_aktivitas_reward)->when(!empty($id_kelas), function ($q) {
            $q->where('is_aktif', 1)->where('is_guru', 1);
        })->get();
        $html = '<option value="0"> Semua </option>';
        foreach ($data_aktivitas_reward as $data) {
            // if (!empty($id_kelas) && $data_reward_siswa->firstWhere('id_event', $data->id_aktivitas_reward_siswa)) {
            //     $html .= '<option value="' . $data->id_aktivitas_reward_siswa . '">' . $data->nm_aktivitas_reward_siswa . ' (Sudah diinput)</option>';
            // } else {
            // }
            $html .= '<option value="' . $data->id_aktivitas_reward_siswa . '">' . $data->nm_aktivitas_reward_siswa . '</option>';
        }

        return $html;
    }

    public function viewRekapInputRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now();

        $id_aktivitas_reward = intval($input->id_aktivitas_reward ?? 0);
        $id_jenis = intval($input->jenis ?? 1);
        $month = intval($input->month ?? $now->month);
        $year = intval($input->year ?? $now->year);

        if ($month || $year) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
        } else {
            $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
            $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();
        }

        $dates = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $week_dates = [];
        $count_per_day = [];

        $i = $startOfMonth;
        while ($i < $endOfMonth) {
            $week['start'] = $i;
            $week['end'] = Carbon::parse($i)->endOfWeek();

            $week_dates[] = $week;
            $i = Carbon::parse($week['end'])->addDay();
        }

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_jenis_aktivitas = JenisAktivitasReward::get();

        $query_data_reward_siswa = DB::table('reward_siswa')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        $query_data_pengisian_harian = DB::table('pengisian_kegiatan_harian')->whereBetween('tgl_pengisian', [$startOfMonth, $endOfMonth]);

        $query_data_reward_siswa_join = DB::table('pengisian_kegiatan_harian as pkh')
            ->select(
                'pkh.*',
                'rs.*',
                // 'pkh.id_pengguna_pengisi',
                // 'pkh.id_kegiatan_harian',
                // 'pkh.id_pengisian_kegiatan_harian',
                // 'pkh.tgl_pengisian',
                // 'pkh.created_by'
            )
            // join dengan mencari id_siswa tersebut
            // yaitu dengan id_pengguna_pengisi dan id_pengguna_reward_siswa
            ->leftJoin('reward_siswa as rs', 'pkh.id_pengguna_pengisi', '=', 'rs.id_pengguna_reward_siswa');

        if (!empty($input->jenis)) {
            $list_data = LibSiswa::fetchDataSiswa($auth_data, $input->id_kelas);
            $data_aktivitas_reward = AktivitasRewardSiswa::where('id_jenis_aktivitas_reward', $input->jenis)->get();

            if ($id_aktivitas_reward === 0) {
                // aktifitas input semua dari pengisian kegiatan harian
                if ($id_jenis === 1) {
                    $data_reward_siswa = $query_data_pengisian_harian
                        ->where('id_kegiatan_harian', 'reward-siswa-harian')
                        ->get();
                } elseif ($id_jenis === 2) {
                    $data_reward_siswa = $query_data_pengisian_harian
                        ->where('id_kegiatan_harian', 'reward-siswa-mingguan')
                        ->get();
                } elseif ($id_jenis === 3) {
                    $data_reward_siswa = $query_data_pengisian_harian
                        ->where('id_kegiatan_harian', 'reward-siswa-bulanan')
                        ->get();
                } else {
                    // kegiatan isidentil akan masuk ke error karena belum diperlukan
                    return  [
                        'status' => 500, // FAILED 
                        'message' => 'Jenis aktifitas tidak valid',
                        'path' => "reward-siswa/rekap-input-reward-siswa?jenis=$input->jenis&id_aktivitas_reward=$input->id_aktifitas_reward&id_kelas=$input->id_kelas&month=$input->month&year=$input->year",
                    ];
                }
            } else {
                // aktifitas input berdasarkan id_event reward siswa
                if ($id_jenis === 1) {
                    $data_reward_siswa = $query_data_reward_siswa
                        ->where('id_event', $input->id_aktivitas_reward)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->get();
                } elseif ($id_jenis === 2) {
                    $data_reward_siswa = $query_data_reward_siswa
                        ->where('id_event', $input->id_aktivitas_reward)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->get();
                } elseif ($id_jenis === 3) {
                    $data_reward_siswa = $query_data_reward_siswa
                        ->where('id_event', $input->id_aktivitas_reward)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->get();
                } else {
                    // kegiatan isidentil akan masuk ke error karena belum diperlukan
                    return  [
                        'status' => 500, // FAILED 
                        'message' => 'Jenis aktifitas tidak valid',
                        'path' => "reward-siswa/rekap-input-reward-siswa?jenis=1&id_aktivitas_reward=$input->id_aktifitas_reward&id_kelas=$input->id_kelas&month=$input->month&year=$input->year",
                    ];
                }
            }
        } else {
            $data_aktivitas_reward = [];
            $data_reward_siswa = [];
            $list_data = [];
        }

        return view('guru/reward-siswa/input-reward-siswa/rekap-input-reward-siswa', compact('auth_data', 'dates', 'week_dates', 'data_reward_siswa', 'data_jenis_aktivitas', 'data_aktivitas_reward', 'list_data', 'data_kelas', 'now', 'id_aktivitas_reward'));
    }

    public function actionViewInputRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        // dd($input);
        // dd($input->id_aktivitas_reward);
        $auth_data = $input->auth_data;

        // $validator = Validator::make($request->all(), [
        //     'jenis_aktivitas'   => 'required',
        //     'aktivitas_reward'  => 'required',
        //     'id_kelas'          => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return [
        //         'status' => 300, // FAILED
        //         'message' => $validator->errors()->first()
        //     ];
        // } else {
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'message' => 'Lanjut proses data siswa',
            'path' => "reward-siswa/input-reward-siswa/view-kelas/$input->id_kelas/$input->aktivitas_reward",
        ];
        // return redirect("guru/reward-siswa/input-reward-siswa/view-kelas/$input->id_kelas/$input->aktivitas_reward");
        // }
    }

    public function viewKelasInputRewardSiswa(Request $request, $id_kelas, $aktivitas_reward)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_aktivitas_reward = AktivitasRewardSiswa::where('id_aktivitas_reward_siswa', $aktivitas_reward)->where('is_guru', 1)->where('is_aktif', 1)->first();

        if ($data_aktivitas_reward->id_jenis_aktivitas_reward == 1) {
            $date_input = 'tanggal ' . Carbon::now('Asia/Jakarta')->isoFormat('D MMM Y');
        } else if ($data_aktivitas_reward->id_jenis_aktivitas_reward == 2) {
            $date_input = 'minggu ini tanggal (' . Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('D MMM Y') . ' - ' . Carbon::now('Asia/Jakarta')->endOfWeek()->isoFormat('D MMM Y') . ')';
        } else if ($data_aktivitas_reward->id_jenis_aktivitas_reward == 3) {
            $date_input = 'bulan ' . Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('MMMM');
        } else if ($data_aktivitas_reward->id_jenis_aktivitas_reward == 4) {
            // insidentil
            $date_input = 'bulan ' . Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('MMMM');
        }

        return view('guru/reward-siswa/input-reward-siswa/view-kelas-input-reward-siswa', compact('auth_data', 'semester_aktif', 'data_kelas', 'aktivitas_reward', 'data_aktivitas_reward', 'date_input'));
    }

    public function datatablesInputRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswa($auth_data, $input->id_kelas);
        $now =  Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $data_aktivitas_reward = AktivitasRewardSiswa::where('id_aktivitas_reward_siswa', $input->id_aktivitas_reward_siswa)
            ->where('is_guru', 1)->where('is_aktif', 1)->get();

        $data_reward_siswa = RewardSiswa::where('id_kelas', $input->id_kelas)
            ->where('id_event', $input->id_aktivitas_reward_siswa)->whereDate('created_at', $now)->pluck('id_siswa')->toArray();

        return Datatables::of($list_data)
            ->addColumn('aktivitas_reward', function ($item) use ($data_aktivitas_reward, $data_reward_siswa) {
                return array(
                    'list' => $data_aktivitas_reward->map(function ($x) use ($item, $data_reward_siswa) {
                        return [
                            'id_aktivitas_reward_siswa' => $x->id_aktivitas_reward_siswa,
                            'nm_aktivitas_reward_siswa' => $x->nm_aktivitas_reward_siswa,
                            'is_filled' => in_array($item->id_siswa, $data_reward_siswa) ? true : false,
                        ];
                    })
                );
            })
            ->make(true);
    }

    public function saveRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();
        $validator = Validator::make($request->all(), [
            // 'uraian_materi' => 'required',
            // 'waktu_mulai' => 'required',
            // 'waktu_selesai' => 'required',
            // 'tgl_presensi' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $data_master_aktivitas_reward = AktivitasRewardSiswa::where('id_jenis_aktivitas_reward', $input->jenis_aktivitas_reward)->where('is_guru', 1)->where('is_aktif', 1)->get();
            foreach ($input->id_aktivitas_reward_siswa as $id_siswa => $data_aktivitas_reward) {
                foreach ($data_aktivitas_reward as $id_aktivitas_reward) {
                    $id_reward_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $reward_siswa = new RewardSiswa();
                    $reward_siswa->id_reward_siswa = $id_reward_siswa;
                    $reward_siswa->model_event = 'NonKBM';

                    $reward_siswa->id_event = $id_aktivitas_reward;
                    $reward_siswa->id_kelas = $input->id_kelas;
                    $reward_siswa->id_siswa = $id_siswa;

                    $reward_siswa->nm_reward_siswa = $data_master_aktivitas_reward->firstWhere('id_aktivitas_reward_siswa', $id_aktivitas_reward)->nilai_karakter;
                    $reward_siswa->id_pengguna_reward_siswa = $input->auth_data->pengguna->id_pengguna;
                    $reward_siswa->created_by = $input->auth_data->pengguna->id_pengguna;
                    $reward_siswa->save();
                }
            }

            return [
                'status' => 202, // Berhasil
                'path' => 'reward-siswa/rekap-input-reward-siswa', // Berhasil
                'message' => 'Input reward siswa berhasil'
            ];
        }
    }

    public function datatablesRekapInputRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date = Carbon::now()->subMonths(3)->format('Y-m-d');

        $list_data = RewardSiswa::with('siswa', 'siswa.pengguna', 'kelas', 'pemberi_reward')->where('created_at', '>=', $date . ' 00:00:00')->orderBy('created_at', 'desc');

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_reward_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function addInputRewardSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $id_siswa);

        return view('guru/reward-siswa/input-reward-siswa/add-input-reward-siswa', compact('auth_data', 'data_siswa'));
    }

    public function editInputRewardSiswa(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $reward_siswa = RewardSiswa::find($id);

        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $reward_siswa->id_siswa);

        return view('guru/reward-siswa/input-reward-siswa/edit-input-reward-siswa', compact('auth_data', 'reward_siswa', 'data_siswa'));
    }

    // Action POST
    public function actionInputRewardSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas'              => 'required',
            'id_siswa'              => 'required',
            'nm_reward_siswa'    => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                if ($siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first()) {
                    $reward_siswa                               = new RewardSiswa;

                    $reward_siswa->id_reward_siswa   = $id;
                    $reward_siswa->id_siswa                     = $input->id_siswa;
                    $reward_siswa->id_kelas                     = $input->id_kelas;
                    $reward_siswa->id_pengguna_reward_siswa     = $input->auth_data->pengguna->id_pengguna;
                    $reward_siswa->nm_reward_siswa              = $input->nm_reward_siswa;
                    $reward_siswa->deskripsi_reward_siswa       = $input->deskripsi_reward_siswa;
                    // convert format date
                    $reward_siswa->created_by                   = $input->auth_data->pengguna->id_pengguna;
                    $reward_siswa->save();

                    $token_siswa = $siswa->pengguna->api_token;
                    if (!empty($token_siswa)) {
                        $message = 'Kamu telah tercatat mendapatkan reward dari guru';
                        $send_data = array(
                            'title' => 'Informasi',
                            'body' => $message,
                            'priority' => 'high',
                            'screen1' => '',
                            'screen2' => ''
                        );

                        $notifikasi = array(
                            'id' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_pengguna' => $siswa->pengguna->id_pengguna,
                            'id_sekolah' => $siswa->pengguna->id_sekolah,
                            'isi_notifikasi' => $message,
                            'created_by' => $input->auth_data->pengguna->id_pengguna
                        );

                        LibGlobal::sendNotification($token_siswa, $send_data, $notifikasi);
                    }

                    if (!empty($siswa->id_wali_murid)) {
                        $wali_murid = WaliMurid::find($siswa->id_wali_murid);

                        $token_wali_murid = $wali_murid->pengguna->api_token;
                        if (!empty($token_wali_murid)) {
                            $message = 'Putra/Putri Anda telah tercatat mendapatkan reward dari guru';
                            $send_data = array(
                                'title' => 'Informasi',
                                'body' => $message,
                                'priority' => 'high',
                                'screen1' => '',
                                'screen2' => ''
                            );

                            $notifikasi = array(
                                'id' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                                'id_pengguna' => $wali_murid->pengguna->id_pengguna,
                                'id_sekolah' => $wali_murid->pengguna->id_sekolah,
                                'isi_notifikasi' => $message,
                                'created_by' => $input->auth_data->pengguna->id_pengguna
                            );

                            LibGlobal::sendNotification($token_wali_murid, $send_data, $notifikasi);
                        }
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'reward-siswa/rekap-input-reward-siswa',
                    'message' => 'Input Reward Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $reward_siswa                               = RewardSiswa::find($id);
                $reward_siswa->nm_reward_siswa              = $input->nm_reward_siswa;
                $reward_siswa->deskripsi_reward_siswa       = $input->deskripsi_reward_siswa;
                // convert format date
                $reward_siswa->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $reward_siswa->updated_at                   = $now;
                $reward_siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'reward-siswa/rekap-input-reward-siswa',
                    'message' => 'Update Reward Siswa Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $reward_siswa               = RewardSiswa::find($id);
                $reward_siswa->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $reward_siswa->save();

                $reward_siswa->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Reward Siswa Successfully'
                ];
            }
        }
    }
}
