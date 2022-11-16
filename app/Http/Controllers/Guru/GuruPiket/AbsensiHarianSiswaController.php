<?php

namespace App\Http\Controllers\Guru\GuruPiket;

use DB;
use Auth;

use Session;
use Validator;
use Carbon\Carbon;

use App\Models\Guru;
use App\Models\Bulan;
use App\Models\Siswa;
use App\Models\WaliKelas;
use App\Models\WaliMurid;
use App\Libraries\LibGlobal;

use Illuminate\Http\Request;
use App\Models\PresensiHarian;
use Yajra\Datatables\Datatables;
use App\Models\PresensiHarianSiswa;
use Illuminate\Support\Facades\App;
use App\Libraries\SumberDaya\LibGuru;

use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use Illuminate\Routing\Controller as BaseController;

class AbsensiHarianSiswaController extends BaseController
{
    protected $modul_url = 'guru-piket';
    protected $menu_url = 'absensi-harian-siswa';

    public function viewAbsensiHarianSiswa(Request $request, $id_semester = null, $id_kelas = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if (!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

        $selected_kelas = null;
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        if (!empty($id_kelas)) {
            $selected_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        }

        return view(
            'guru/guru-piket/absensi-harian-siswa/view-absensi-harian-siswa',
            compact('auth_data', 'selected_kelas', 'data_kelas', 'selected_semester', 'data_semester')
        );
    }

    public function viewManageAbsensiHarianSiswa(Request $request, $id_semester, $id_kelas, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $presensi_harian = null;
        if (!empty($id)) {
            $presensi_harian = PresensiHarian::find($id);
        }

        return view(
            'guru/guru-piket/absensi-harian-siswa/manage-absensi-harian-siswa',
            compact('auth_data', 'data_kelas', 'data_semester', 'presensi_harian')
        );
    }

    public function viewDetailAbsensiHarianSiswa(Request $request, $id_semester, $id_kelas, $tahun, $id_bulan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;


        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);

        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, null, 'all');

        $bulan = Bulan::find($id_bulan);

        $start_date = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');

        $end_date = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta')->endOfMonth();

        $data_presensi = PresensiHarian::with('jadwal_hari', 'presensi_harian_siswa')
            ->where('id_semester', $id_semester)
            ->where('id_kelas', $id_kelas)
            ->whereBetween('tgl_entry', [$start_date, $end_date])
            ->orderBy('tgl_entry', 'asc')
            ->get();

        return view(
            'guru/guru-piket/absensi-harian-siswa/view-detail-absensi-harian-siswa',
            compact('auth_data', 'bulan', 'semester_aktif', 'data_kelas', 'data_siswa', 'data_presensi', 'tahun', 'id_bulan')
        );
    }
    public function printDetailAbsensiHarianSiswa(Request $request, $id_semester, $id_kelas, $id_pengguna, $id_bulan, $tahun)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $id_pengguna);

        $start_date = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');

        $end_date = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta')->endOfMonth();

        $data_presensi = PresensiHarian::with('jadwal_hari', 'presensi_harian_siswa')
            ->where('id_semester', $id_semester)
            ->where('id_kelas', $id_kelas)
            ->whereBetween('tgl_entry', [$start_date, $end_date])
            ->orderBy('tgl_entry', 'asc')
            ->get();

        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();

        // dd($wali_kelas);

        return view('guru/guru-piket/absensi-harian-siswa/print-detail-absensi-harian-siswa', compact('auth_data', 'semester_aktif', 'siswa', 'data_presensi', 'wali_kelas'));
    }

    public function datatablesAbsensiHarianSiswa(Request $request, $id_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $bulan = Bulan::get();

        $list_data = PresensiHarian::selectRaw('COUNT(*) as jml_record, YEAR(tgl_entry) tahun, MONTH(tgl_entry) bulan')
                                    ->where('id_semester', $id_semester)
                                    ->where('id_kelas', $id_kelas)
                                    ->groupBy(DB::raw('YEAR(tgl_entry),  MONTH(tgl_entry)'));
                                    
        return Datatables::of($list_data)
                ->editColumn('bulan', function ($item) use ($bulan) {
                    return $bulan->firstWhere('id_bulan', $item->bulan)->nm_bulan;
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'tahun' => $item->tahun,
                        'bulan' => $item->bulan
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesKelasAbsensiHariSiswa(Request $request, $id_semester, $id_kelas, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, null, 'only-aktif');

        if (!empty($id)) {
            $presensi_harian = PresensiHarian::find($id);
            $presensi_harian_siswa = PresensiHarianSiswa::where('id_presensi_harian', '=', $presensi_harian->id_presensi_harian)->get();
        } else {
            $presensi_harian = null;
            $presensi_harian_siswa = null;
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
            ->addColumn('alasan', function ($item) use ($presensi_harian_siswa) {
                $kehadiran = null;
                if ($presensi_harian_siswa && $selected_presensi_harian_siswa = $presensi_harian_siswa->firstWhere('id_siswa', $item->id_siswa)) {
                    $kehadiran = $selected_presensi_harian_siswa->kehadiran;
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

    public function actionAbsensiHarianSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_semester' => 'required',
            'tgl_entry' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if ($mode == 'manage') {
                DB::beginTransaction();
                try {
                    $tgl_entry = Carbon::parse($input->tgl_entry);
                    if (!empty($input->id_presensi_harian)) {
                        $presensi_harian = PresensiHarian::find($input->id_presensi_harian);
                    } else {
                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $presensi_harian = new PresensiHarian;
                        $presensi_harian->id_presensi_harian = $id;
                        $presensi_harian->id_guru_entry = $input->auth_data->pengguna->id_pengguna;
                        $presensi_harian->id_kelas = $input->id_kelas;
                        $presensi_harian->id_semester = $input->id_semester;
                    }
                    $presensi_harian->id_jadwal_hari = ($tgl_entry->dayOfWeek == 0)? 7 : $tgl_entry->dayOfWeek;
                    $presensi_harian->tgl_entry = $tgl_entry;
                    $presensi_harian->save();

                    $total_siswa = 0;
                    $total_siswa_masuk = 0;

                    // presensi_harian_siswa
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        if (! empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }

                        if ($presensi_harian_siswa = PresensiHarianSiswa::where('id_presensi_harian', '=', $presensi_harian->id_presensi_harian)->where('id_siswa', '=', $id_siswa)->first()) {
                            $presensi_harian_siswa->updated_by                = $input->auth_data->pengguna->id_pengguna;
                        } else {
                            // make id
                            $id_presensi_harian_siswa = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                            $presensi_harian_siswa                            = new PresensiHarianSiswa;
                            $presensi_harian_siswa->id_presensi_harian        = $presensi_harian->id_presensi_harian;
                            $presensi_harian_siswa->id_presensi_harian_siswa  = $id_presensi_harian_siswa;
                            $presensi_harian_siswa->created_by                = $input->auth_data->pengguna->id_pengguna;

                            if($siswa = Siswa::find($id_siswa)){
                                if(!empty($siswa->id_wali_murid)){
                                    $wali_murid = WaliMurid::find($siswa->id_wali_murid);

                                    switch($kehadiran){
                                        case 1:
                                            $status = 'Hadir'; break;
                                        case 2:
                                            $status = 'Sakit'; break;
                                        case 3:
                                            $status = 'Izin'; break;
                                        case 4:
                                            $status = 'Alpa'; break;
                                    }

                                    if($wali_murid){
                                        $token_wali_murid = $wali_murid->pengguna->api_token;
                                        if(!empty($token_wali_murid)){
                                            $message = 'Putra/Putri Anda hari ini berstatus '.$status;
                                            $send_data = array(
                                                'title' => 'Informasi',
                                                'body' => $message,
                                                'priority' => 'high',
                                                'screen1' => 'MainMenu',
                                                'screen2' => 'MainMenu'
                                            );

                                            $notifikasi = array(
                                                'id' => $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid(),
                                                'id_pengguna' => $wali_murid->pengguna->id_pengguna,
                                                'id_sekolah' => $wali_murid->pengguna->id_sekolah,
                                                'isi_notifikasi' => $message,
                                                'created_by' => $input->auth_data->pengguna->id_pengguna
                                            );

                                            LibGlobal::sendNotification($token_wali_murid, $send_data, $notifikasi);
                                        }   
                                    }
                                }
                            }
                        }

                        $presensi_harian_siswa->id_siswa                    = $id_siswa;
                        $presensi_harian_siswa->kehadiran                   = $kehadiran;
                        $presensi_harian_siswa->save();

                        if ($kehadiran == 1) {
                            $total_siswa++;
                            $total_siswa_masuk++;
                        } else {
                            $total_siswa++;
                        }
                    }

                    $presensi_harian->persentase_presensi_harian = ($total_siswa_masuk / $total_siswa);
                    $presensi_harian->save();

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'guru-piket/absensi-harian-siswa/'.$input->id_semester.'/'.$input->id_kelas,
                        'message' => 'Save Absensi Harian Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' => 'Absensi Harian Gagal!'
                    ];
                }
            } elseif ($mode == 'delete') {
                DB::beginTransaction();
                try {
                    $presensi_harian = PresensiHarian::where('id_presensi_harian', $id)->delete();
                    $presensi_harian_siswa = PresensiHarianSiswa::where('id_presensi_harian', $id)->delete();

                    DB::commit();
                    // all good

                    return [
                        'status' => 200, // SUCCESS AND LOAD DATATABLES
                        'message' => 'Delete Absensi Harian Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' => 'Absensi Harian Gagal!'
                    ];
                }
            }
        }
    }
}
