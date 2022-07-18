<?php

namespace App\Http\Controllers\Guru\RewardSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PresensiMp as PresensiMp;
use App\Models\PresensiMpPelanggaran as PresensiMpPelanggaran;
use App\Models\Siswa as Siswa;
use App\Models\WaliMurid;
use App\Models\RewardSiswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\LibGlobal;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class InputRewardSiswaController extends BaseController
{
    public function viewInputRewardSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('guru/reward-siswa/input-reward-siswa/view-input-reward-siswa', compact('auth_data', 'data_kelas'));
    }

    public function viewRekapInputRewardSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/reward-siswa/input-reward-siswa/rekap-input-reward-siswa', compact('auth_data'));
    }

    public function actionViewInputRewardSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'reward-siswa/input-reward-siswa/view-kelas/' . $input->id_kelas
            ];
        }
    }

    public function viewKelasInputRewardSiswa(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        return view('guru/reward-siswa/input-reward-siswa/view-kelas-input-reward-siswa', compact('auth_data', 'semester_aktif', 'data_kelas'));
    }

    public function datatablesInputRewardSiswa(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswa($auth_data, $id_kelas);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesRekapInputRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = RewardSiswa::with('siswa', 'siswa.pengguna', 'kelas', 'pemberi_reward');

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
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $id_siswa);

        return view('guru/reward-siswa/input-reward-siswa/add-input-reward-siswa', compact('auth_data', 'data_siswa'));
    }

    public function editInputRewardSiswa(Request $request, $id)
    {
        # code...
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
            $now = Carbon::now(env('APP_TIMEZONE', ''));

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
                    'message' => 'Input Reward Siswa successfully'
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
                    'message' => 'Update Reward Siswa successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $reward_siswa               = RewardSiswa::find($id);
                $reward_siswa->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $reward_siswa->save();

                $reward_siswa->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Reward Siswa successfully'
                ];
            }
        }
    }
}
