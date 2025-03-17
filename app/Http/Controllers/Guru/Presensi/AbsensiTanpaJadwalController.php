<?php

namespace App\Http\Controllers\Guru\Presensi;

use App\Models\JadwalKelasMp;
use App\Models\MapelRPP;
use App\Models\MapelRPPDetail;
use App\Models\RewardSiswa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\UjianMpPresensi;
use App\Models\PengampuMapel;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\KelasMp;
use App\Models\PengampuMp;
use App\Models\Kelas;


use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class AbsensiTanpaJadwalController extends BaseController
{
    public function viewAbsensiTanpaJadwal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tanggal = Carbon::now();
        $tanggal_id = $tanggal->format('d F Y');
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $id_guru = $guru->id_guru;

        $jadwal_kelas_mp = JadwalKelasMp::with('kelas_mp')
            ->join('kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->where('id_semester', $semester_aktif->id_semester)
            ->where('jadwal_kelas_mp.id_jadwal_hari',  '0')
            ->where('pengampu_mp.id_guru', $id_guru)
            ->get();

        $kelas = LibKelas::fetchDataKelas($auth_data, $id = null);

        return view('guru/presensi/absensi-tanpa-jadwal/view-absensi-tanpa-jadwal', compact('auth_data', 'semester_aktif', 'tanggal', 'tanggal_id', 'kelas', 'jadwal_kelas_mp'));
    }

    public function ajaxGetChangePertemuanByJadwalKelasMp(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd("masuk");
        $id_jadwal_kelas_mp = $input->id_jadwal_kelas_mp;

        $data_pertemuan = array();
        $data_presensiMp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->get();

        for ($i = 1; $i < 26; $i++) {
            $presensiMp = $data_presensiMp->firstWhere('pertemuan_ke', $i);
            if ($presensiMp) {
                $pertemuan = array(
                    'text' => $i . " (Sudah)",
                    'value' => $i
                );
            } else {
                $pertemuan = array(
                    'text' => $i,
                    'value' => $i
                );
            }

            $data_pertemuan[] = $pertemuan;
        }

        return $data_pertemuan;
    }


    public function actionViewKBMAbsensiTanpaJadwal(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd()
        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required',
            'opsi' => 'required'
        ]);
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        if ($input->opsi == !null) {
            $opsi = $input->opsi;
        } else {
            $opsi = 0;
        }

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/absensi-tanpa-jadwal/view-kbm/' . $input->id_jadwal_kelas_mp . '/' . $input->pertemuan_ke . '/' . $opsi
            ];
        }
    }

    public function viewKBMAbsensiTanpaJadwal(Request $request, $id_jadwal_kelas_mp, $pertemuan_ke, $opsi = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tanggal = $now->format('Y-m-d');

        $data = PresensiMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)
            ->where('pertemuan_ke', $pertemuan_ke)
            ->first();

        $id_kelas_mp = JadwalKelasMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->value('id_kelas_mp');
        $kelas_mp = KelasMp::with('kelas', 'mata_pelajaran')->where('id_kelas_mp', $id_kelas_mp)->first();

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();

        if ($mapel_rpp = MapelRPP::where('id_semester', $semester_aktif->id_semester)->where('id_mata_pelajaran', $kelas_mp->id_mata_pelajaran)->first()) {
            $mapel_rpp_detail = MapelRPPDetail::where('id_mapel_rpp', $mapel_rpp->id_mapel_rpp)->where('pertemuan_ke', $pertemuan_ke)->first();
        } else {
            $mapel_rpp_detail = null;
        }

        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        return view('guru/presensi/absensi-tanpa-jadwal/view-kbm-absensi-tanpa-jadwal', compact('auth_data', 'data', 'opsi', 'tanggal', 'data_kelas', 'pertemuan_ke', 'opsi', 'semester_aktif', 'kelas_mp', 'id_jadwal_kelas_mp', 'presensi_mp_aktif', 'mapel_rpp_detail'));
    }

    public function datatablesKBMAbsensiTanpaJadwal(Request $request, $id_jadwal_kelas_mp, $pertemuan_ke)
    {
        // dd($id_jadwal_kelas_mp, $pertemuan_ke);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        $id_kelas_mp = JadwalKelasMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->value('id_kelas_mp');
        $id_mata_pelajaran = KelasMp::where('id_kelas_mp', $id_kelas_mp)->value('id_mata_pelajaran');
        $list_data = LibSiswa::fetchDataSiswaKelasMpTanpaPloting($auth_data, $id_jadwal_kelas_mp, $pertemuan_ke);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();

        if ($presensi_mp_aktif) {
            $data_presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp_aktif->id_presensi_mp)->get();
        } else {
            $data_presensi_mp_siswa = null;
        }

        if ($mapel_rpp = MapelRPP::where('id_semester', $semester_aktif->id_semester)->where('id_mata_pelajaran', $id_mata_pelajaran)->first()) {
            $mapel_rpp_detail = MapelRPPDetail::where('id_mapel_rpp', $mapel_rpp->id_mapel_rpp)->where('pertemuan_ke', $pertemuan_ke)->first();
        } else {
            $mapel_rpp_detail = null;
        }

        return Datatables::of($list_data)
            ->editColumn('nis_siswa', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa,
                    'nis_siswa' => $item->nis_siswa,
                    'status_pengguna' => array(
                        'status' => $item->aktif_status_pengguna,
                        'nm_status' => $item->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->addColumn('alasan', function ($item) use ($data_presensi_mp_siswa) {
                $kehadiran = null;
                if ($data_presensi_mp_siswa && $presensi_mp_siswa = $data_presensi_mp_siswa->firstWhere('id_siswa', $item->id_siswa)) {
                    $kehadiran = $presensi_mp_siswa->kehadiran;
                }
                $options = array(
                    array('id' => 1, 'text' => 'Hadir'),
                    array('id' => 2, 'text' => 'Sakit'),
                    array('id' => 3, 'text' => 'Izin'),
                    array('id' => 4, 'text' => 'Alpa'),
                );
                $data = array(
                    'options' => $options,
                    'kehadiran' => $kehadiran,
                    'status_pengguna' => array(
                        'status' => $item->aktif_status_pengguna,
                        'nm_status' => $item->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->addColumn('nilai_karakter', function ($item) use ($mapel_rpp_detail) {
                if (!empty($mapel_rpp_detail)) {
                    $options = explode('#', $mapel_rpp_detail->nilai_karakter);
                } else {
                    $options = [];
                }
                $data = array(
                    'options' => $options,
                    'status_pengguna' => array(
                        'status' => $item->aktif_status_pengguna,
                        'nm_status' => $item->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionAbsensiTanpaJadwal(Request $request, $mode, $id = null, $pertemuan_ke = null, $id_jadwal_kelas_mp = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // return response()->json($input);
        $validator = Validator::make($request->all(), [
            'uraian_materi' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tgl_presensi' => 'required'
        ]);
        if ($validator->fails() && $mode == 'add-kbm') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now();

            // ACTION ADD
            if ($mode == 'add-kbm') {
                $id_jadwal_kelas_mp = $id;
                // dd('add');
                $presensi_mp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();
                if (isset($input->pertemuan_id)) {
                    $pertemuan_ke = $input->pertemuan_id;
                }
                DB::beginTransaction();
                try {
                    if ($presensi_mp) {
                        $presensi_mp->updated_by = $input->auth_data->pengguna->id_pengguna;
                    } else {
                        // make id
                        $id_presensi_mp = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                        $auth_data = $input->auth_data;
                        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
                        $data_kelas = LibGuru::fetchDataJadwalKBM(
                            $auth_data,
                            $auth_data->pengguna->id_pengguna,
                            $id_kelas_mp = JadwalKelasMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)->value('id_kelas_mp'),
                            $semester_aktif->id_semester,
                            null,
                            $id_jadwal_kelas_mp
                        );

                        $presensi_mp = new PresensiMp;
                        $presensi_mp->id_presensi_mp = $id_presensi_mp;
                        $presensi_mp->id_kelas_mp = $id_kelas_mp;
                        // $presensi_mp->id_kelas_mp = $data_kelas->id_kelas_mp;
                        $presensi_mp->id_jadwal_kelas_mp = $id_jadwal_kelas_mp;
                        $presensi_mp->pertemuan_ke = $pertemuan_ke;
                        $presensi_mp->tgl_entry = $now;
                        $presensi_mp->created_by = $input->auth_data->pengguna->id_pengguna;
                    }

                    $presensi_mp->uraian_materi = $input->uraian_materi;
                    $presensi_mp->waktu_mulai = $input->waktu_mulai;
                    $presensi_mp->waktu_selesai = $input->waktu_selesai;
                    $presensi_mp->tgl_presensi = $input->tgl_presensi;
                    $presensi_mp->save();

                    // PresensiMpSiswa
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        if (!empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }

                        if ($presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp->id_presensi_mp)->where('id_siswa', '=', $id_siswa)->first()) {
                            $presensi_mp_siswa->updated_by = $input->auth_data->pengguna->id_pengguna;
                        } else {
                            // make id
                            $id_presensi_mp_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                            $presensi_mp_siswa = new PresensiMpSiswa;
                            $presensi_mp_siswa->id_presensi_mp_siswa = $id_presensi_mp_siswa;
                            $presensi_mp_siswa->id_presensi_mp = $presensi_mp->id_presensi_mp;
                            $presensi_mp_siswa->created_by = $input->auth_data->pengguna->id_pengguna;
                            $presensi_mp_siswa->id_siswa = $id_siswa;
                        }

                        $presensi_mp_siswa->kehadiran = $kehadiran;
                        $presensi_mp_siswa->save();
                    }
                    if (isset($input->id_karakter_siswa)) {
                        $data_karakter_siswa = $input->id_karakter_siswa;

                        foreach ($input->id_siswa as $id_siswa) {
                            RewardSiswa::where('model_event', 'PresensiMp')->where('id_event', $presensi_mp->id_presensi_mp)->delete();
                            // make id

                            if (isset($data_karakter_siswa[$id_siswa])) {
                                foreach ($data_karakter_siswa[$id_siswa] as $karakter_siswa) {

                                    $id_reward_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                                    $reward_siswa = new RewardSiswa();
                                    $reward_siswa->id_reward_siswa = $id_reward_siswa;
                                    $reward_siswa->model_event = 'PresensiMp';
                                    $reward_siswa->id_event = $presensi_mp->id_presensi_mp;
                                    $reward_siswa->id_kelas = $id_siswa;
                                    $reward_siswa->id_siswa = $presensi_mp->kelas_mp->id_kelas;
                                    $reward_siswa->nm_reward_siswa = $karakter_siswa;
                                    $reward_siswa->id_pengguna_reward_siswa = $input->auth_data->pengguna->id_pengguna;
                                    $reward_siswa->created_by = $input->auth_data->pengguna->id_pengguna;
                                    $reward_siswa->save();
                                }
                            }
                        }
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi/absensi-tanpa-jadwal',
                        'message' => 'Save Absensi KBM Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    dd($e);
                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Absensi KBM Gagal!'
                    ];
                }
            } elseif ($mode == 'delete-kbm') {
                $presensi_mp = PresensiMp::where('id_jadwal_kelas_mp', $id)
                    ->where('pertemuan_ke', $pertemuan_ke)->get();

                foreach ($presensi_mp as $data) {
                    $data->deleted_by = $input->auth_data->pengguna->id_pengguna;
                    $data->save();
                    $data->delete();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'presensi/absensi-tanpa-jadwal/',
                    'message' => 'Delete Absensi KBM Siswa Successfully'
                ];
            }
        }
    }
}
