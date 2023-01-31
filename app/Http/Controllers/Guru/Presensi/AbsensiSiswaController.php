<?php

namespace App\Http\Controllers\Guru\Presensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PresensiMp as PresensiMp;
use App\Models\PresensiMpSiswa as PresensiMpSiswa;
use App\Models\UjianMpPresensi as UjianMpPresensi;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Setting;
use Auth;
use DB;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
use Session;
use Validator;

class AbsensiSiswaController extends BaseController
{
    public function viewAbsensiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $id_jadwal_hari = Carbon::now(env('APP_TIMEZONE', ''))->format('N');

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        $data_uts = LibGuru::fetchDataJadwalUTS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0);

        $data_uas = LibGuru::fetchDataJadwalUAS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0);

        $setting =  Setting::where('key_setting', 'is_presensi_one_day')->pluck('value')->first();

        if ($setting == '1') {
            $grup_kbm_perhari = $data_kbm->where('id_jadwal_hari', $id_jadwal_hari)->groupBy('nm_jadwal_hari');
        } else {
            $grup_kbm_perhari = $data_kbm->groupBy('nm_jadwal_hari');
        }

        return view('guru/presensi/absensi-siswa/view-absensi-siswa', compact('auth_data', 'semester_aktif', 'data_uts', 'data_uas', 'grup_kbm_perhari'));
    }

    public function ajaxGetPertemuanByJadwalKelasMp(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

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

    // ==== ACTION PRESENSI KBM ====
    public function actionViewKBMAbsensiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/absensi-siswa/view-kbm/' . $input->id_jadwal_kelas_mp . '/' . $input->pertemuan_ke
            ];
        }
    }

    public function viewKBMAbsensiSiswa(Request $request, $id_jadwal_kelas_mp, $pertemuan_ke)
    {
        # code... 
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data = PresensiMp::where('id_jadwal_kelas_mp', $id_jadwal_kelas_mp)
            ->where('pertemuan_ke', $pertemuan_ke)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        if (empty($data_kelas)) {
            return redirect("/guru#presensi/absensi-siswa");
        }

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();

        return view('guru/presensi/absensi-siswa/view-kbm-absensi-siswa', compact('auth_data', 'semester_aktif', 'data_kelas', 'pertemuan_ke', 'presensi_mp_aktif', 'id_jadwal_kelas_mp', 'data'));
    }

    public function datatablesKBMAbsensiSiswa(Request $request, $id_jadwal_kelas_mp, $pertemuan_ke)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswaKelasMpTanpaPloting($auth_data, $id_jadwal_kelas_mp, $pertemuan_ke);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();

        if ($presensi_mp_aktif) {
            $data_presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp_aktif->id_presensi_mp)->get();
        } else {
            $data_presensi_mp_siswa = null;
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
            ->make(true);
    }

    // ==== ACTION PRESENSI UTS ====
    public function actionViewUTSAbsensiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_ujian_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/absensi-siswa/view-uts/' . $input->id_ujian_mp
            ];
        }
    }

    public function viewUTSAbsensiSiswa(Request $request, $id_ujian_mp)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalUTS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0, $id_ujian_mp);

        return view('guru/presensi/absensi-siswa/view-uts-absensi-siswa', compact('auth_data', 'semester_aktif', 'data_kelas', 'id_ujian_mp'));
    }

    public function datatablesUTSAbsensiSiswa(Request $request, $id_ujian_mp)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswaUjianMp($auth_data, $id_ujian_mp);
        $data_ujian_mp_presensi = UjianMpPresensi::where('id_ujian_mp', '=', $id_ujian_mp)->get();

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->addColumn('alasan', function ($item) use ($data_ujian_mp_presensi) {
                $kehadiran = null;
                if ($data_ujian_mp_presensi->first() && $ujian_mp_presensi = $data_ujian_mp_presensi->firstWhere('id_siswa', $item->id_siswa)) {
                    $kehadiran = $ujian_mp_presensi->kehadiran;
                }
                $options = array(
                    array('id' => 1, 'text' => 'Hadir'),
                    array('id' => 2, 'text' => 'Sakit'),
                    array('id' => 3, 'text' => 'Izin'),
                    array('id' => 4, 'text' => 'Alpa'),
                );
                $data = array(
                    'options' => $options,
                    'kehadiran' => $kehadiran
                );
                return $data;
            })
            ->make(true);
    }


    // ==== ACTION PRESENSI UAS ====
    public function actionViewUASAbsensiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_ujian_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/absensi-siswa/view-uas/' . $input->id_ujian_mp
            ];
        }
    }

    public function viewUASAbsensiSiswa(Request $request, $id_ujian_mp)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalUAS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0, $id_ujian_mp);

        return view('guru/presensi/absensi-siswa/view-uas-absensi-siswa', compact('auth_data', 'semester_aktif', 'data_kelas', 'id_ujian_mp'));
    }

    public function datatablesUASAbsensiSiswa(Request $request, $id_ujian_mp)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswaUjianMp($auth_data, $id_ujian_mp);
        $data_ujian_mp_presensi = UjianMpPresensi::where('id_ujian_mp', '=', $id_ujian_mp)->get();

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->addColumn('alasan', function ($item) use ($data_ujian_mp_presensi) {
                $kehadiran = null;
                if ($data_ujian_mp_presensi->first() && $ujian_mp_presensi = $data_ujian_mp_presensi->firstWhere('id_siswa', $item->id_siswa)) {
                    $kehadiran = $ujian_mp_presensi->kehadiran;
                }
                $options = array(
                    array('id' => 1, 'text' => 'Hadir'),
                    array('id' => 2, 'text' => 'Sakit'),
                    array('id' => 3, 'text' => 'Izin'),
                    array('id' => 4, 'text' => 'Alpa'),
                );
                $data = array(
                    'options' => $options,
                    'kehadiran' => $kehadiran
                );
                return $data;
            })
            ->make(true);
    }




    // Action POST
    public function actionAbsensiSiswa(Request $request, $mode, $id = null, $pertemuan_ke = null, $id_jadwal_kelas_mp = null)
    {

        $input = (object) $request->input();
        // dd($input);
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
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // Ini untuk apa?
            // $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

            // ACTION ADD
            if ($mode == 'add-kbm') {
                $id_jadwal_kelas_mp = $id;

                $presensi_mp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();

                DB::beginTransaction();
                try {
                    if ($presensi_mp) {
                        $presensi_mp->updated_by         = $input->auth_data->pengguna->id_pengguna;
                    } else {
                        // make id
                        $id_presensi_mp = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                        $auth_data = $input->auth_data;
                        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
                        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

                        $presensi_mp                        = new PresensiMp;
                        $presensi_mp->id_presensi_mp        = $id_presensi_mp;
                        $presensi_mp->id_kelas_mp           = $data_kelas->id_kelas_mp;
                        $presensi_mp->id_jadwal_kelas_mp    = $id_jadwal_kelas_mp;
                        $presensi_mp->pertemuan_ke          = $pertemuan_ke;
                        $presensi_mp->tgl_entry             = $now;
                        $presensi_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                    }

                    $presensi_mp->uraian_materi      = $input->uraian_materi;
                    $presensi_mp->waktu_mulai        = $input->waktu_mulai;
                    $presensi_mp->waktu_selesai      = $input->waktu_selesai;
                    $presensi_mp->tgl_presensi       = $input->tgl_presensi;
                    $presensi_mp->save();

                    // PresensiMpSiswa
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        if (!empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }

                        if ($presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp->id_presensi_mp)->where('id_siswa', '=', $id_siswa)->first()) {
                            $presensi_mp_siswa->updated_by                = $input->auth_data->pengguna->id_pengguna;
                        } else {
                            // make id
                            $id_presensi_mp_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                            $presensi_mp_siswa                            = new PresensiMpSiswa;
                            $presensi_mp_siswa->id_presensi_mp_siswa      = $id_presensi_mp_siswa;
                            $presensi_mp_siswa->id_presensi_mp            = $presensi_mp->id_presensi_mp;
                            $presensi_mp_siswa->created_by                = $input->auth_data->pengguna->id_pengguna;
                            $presensi_mp_siswa->id_siswa                  = $id_siswa;
                        }

                        $presensi_mp_siswa->kehadiran     = $kehadiran;
                        $presensi_mp_siswa->save();
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi/absensi-siswa/view-kbm/' . $id_jadwal_kelas_mp . '/' . $pertemuan_ke,
                        'message' => 'Save Absensi KBM Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Absensi KBM Gagal!'
                    ];
                }
            } elseif ($mode == 'delete-kbm') {

                $favourite_lists = PresensiMp::where('id_jadwal_kelas_mp', $id)
                    ->where('pertemuan_ke', $pertemuan_ke)->get();

                foreach ($favourite_lists as $favourite_list) {
                    $favourite_list->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                    $favourite_list->save();
                    $favourite_list->delete();
                }


                // $data_presensiMp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id)->get();
                // $presensiMp = $data_presensiMp->firstWhere('pertemuan_ke', $pertemuan_ke);

                // $presensiMp->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                // $presensiMp->save();

                // $presensiMp->delete();
                // dd($presensiMp);
                // DB::commit();
                // all good

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'presensi/absensi-siswa/',
                    'message' => 'Delete Absensi KBM Siswa Successfully'
                ];
            } elseif ($mode == 'add-uts') {
                $id_ujian_mp = $id;

                DB::beginTransaction();

                try {
                    // UjianMpPresensi
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        $ujianMpPresensiSet = UjianMpPresensi::where('id_ujian_mp', '=', $id_ujian_mp)->where('id_siswa', '=', $id_siswa)->first();

                        $ujianMpPresensi                = UjianMpPresensi::find($ujianMpPresensiSet->id_ujian_mp_presensi);
                        if (!empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }
                        $ujianMpPresensi->kehadiran     = $kehadiran;
                        $ujianMpPresensi->save();
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi/absensi-siswa/view-uts/' . $id_ujian_mp,
                        'message' => 'Save Absensi UTS Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Absensi UTS Gagal!'
                    ];
                }
            } elseif ($mode == 'add-uas') {
                $id_ujian_mp = $id;

                DB::beginTransaction();

                try {
                    // UjianMpPresensi
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        $ujianMpPresensiSet = UjianMpPresensi::where('id_ujian_mp', '=', $id_ujian_mp)->where('id_siswa', '=', $id_siswa)->first();

                        $ujianMpPresensi                = UjianMpPresensi::find($ujianMpPresensiSet->id_ujian_mp_presensi);
                        if (!empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }
                        $ujianMpPresensi->kehadiran     = $kehadiran;
                        $ujianMpPresensi->save();
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi/absensi-siswa/view-uas/' . $id_ujian_mp,
                        'message' => 'Save Absensi UAS Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Absensi UAS Gagal!'
                    ];
                }
            }
        }
    }
}
