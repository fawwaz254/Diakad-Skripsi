<?php

namespace App\Http\Controllers\PelatihEkskul\AbsensiEkskul;

use App\Exports\ExportPresensiEkskul;
use App\Imports\UploadPresensiEkskul;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Bulan;
use App\Models\Guru;
use App\Models\Ekskul;
use App\Models\PelatihEkskul;
use App\Models\PelatihEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\PresensiEkskulPeserta;
use App\Models\PengambilanEkskul;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\Pendidikan\LibSiswa;
use Maatwebsite\Excel\Facades\Excel;

use Auth;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use DB;
use Illuminate\Support\Facades\Storage;
use Session;
use Validator;

class InputAbsensiEkskulController extends BaseController
{
    protected $modul_url = 'absensi-ekskul';
    protected $menu_url = 'input-absensi-ekskul';

    public function viewInputAbsensiEkskul(Request $request, $id_semester = null, $id_ekskul = null)
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

        $pelatih_ekskul = PelatihEkskul::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $data_ekskul = PelatihEkskulSet::with('ekskul')->where('id_pelatih_ekskul', $pelatih_ekskul->id_pelatih_ekskul)->get();

        return view(
            'pelatih-ekskul/absensi-ekskul/input-absensi-ekskul/view-input-absensi-ekskul',
            compact('auth_data', 'data_ekskul', 'id_ekskul', 'selected_semester', 'data_semester')
        );
    }

    public function viewManageInputAbsensiEkskul(Request $request, $id_semester, $id_ekskul, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_ekskul = Ekskul::find($id_ekskul);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $presensi_ekskul = null;
        if (!empty($id)) {
            $presensi_ekskul = PresensiEkskul::find($id);
        }

        return view(
            'pelatih-ekskul/absensi-ekskul/input-absensi-ekskul/manage-input-absensi-ekskul',
            compact('auth_data', 'data_ekskul', 'data_semester', 'presensi_ekskul')
        );
    }

    public function viewDetailInputAbsensiEkskul(Request $request, $id_semester, $id_ekskul, $tahun, $id_bulan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;


        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_ekskul = Ekskul::find($id_ekskul);

        $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

        $bulan = Bulan::find($id_bulan);

        $start_date = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');

        $end_date = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta')->endOfMonth();

        $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
            ->where('id_ekskul', $id_ekskul)
            ->where('id_semester', $id_semester)
            ->whereBetween('tgl_entry', [$start_date, $end_date])
            ->orderBy('pertemuan_ke', 'asc')
            ->get();
        // dd($start_date);

        return view(
            'pelatih-ekskul/absensi-ekskul/input-absensi-ekskul/view-detail-input-absensi-ekskul',
            compact('auth_data', 'bulan', 'semester_aktif', 'data_ekskul', 'data_siswa', 'data_presensi', 'tahun')
        );
    }

    public function datatablesInputAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $bulan = Bulan::get();

        $list_data = PresensiEkskul::selectRaw('COUNT(*) as jml_record, YEAR(tgl_entry) tahun, MONTH(tgl_entry) bulan')
            ->where('id_semester', $id_semester)
            ->where('id_ekskul', $id_ekskul)
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

    public function datatablesSiswaInputAbsensiEkskul(Request $request, $id_semester, $id_ekskul, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();

        if (!empty($id)) {
            $presensi_ekskul = PresensiEkskul::find($id);
            $presensi_ekskul_peserta = PresensiEkskulPeserta::where('id_presensi_ekskul', '=', $presensi_ekskul->id_presensi_ekskul)->get();
        } else {
            $presensi_ekskul = null;
            $presensi_ekskul_peserta = null;
        }
        return Datatables::of($list_data)
            ->editColumn('nis_siswa', function ($item) {
                $data = array(
                    'id_siswa' => $item->siswa->id_siswa,
                    'nis_siswa' => $item->siswa->nis_siswa,
                    'status_pengguna' => array(
                        'status' => $item->siswa->pengguna->status_pengguna->aktif_status_pengguna,
                        'nm_status' => $item->siswa->pengguna->status_pengguna->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->addColumn('data_kelas', function ($item) {
                $data = array(
                    'id_kelas' => $item->kelas->id_kelas,
                    'nm_kelas' => $item->kelas->nm_kelas,
                    'status_pengguna' => array(
                        'status' => $item->siswa->pengguna->status_pengguna->aktif_status_pengguna,
                        'nm_status' => $item->siswa->pengguna->status_pengguna->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->addColumn('alasan', function ($item) use ($presensi_ekskul_peserta) {
                $kehadiran = null;
                if ($presensi_ekskul_peserta && $selected_presensi_ekskul_peserta = $presensi_ekskul_peserta->firstWhere('id_siswa', $item->siswa->id_siswa)) {
                    $kehadiran = $selected_presensi_ekskul_peserta->kehadiran;
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
                        'status' => $item->siswa->pengguna->status_pengguna->aktif_status_pengguna,
                        'nm_status' => $item->siswa->pengguna->status_pengguna->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->make(true);
    }

    public function actionInputAbsensiEkskul(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'id_ekskul' => 'required',
            'pertemuan_ke' => 'required',
            // 'waktu_mulai' => 'required',
            // 'waktu_selesai' => 'required',
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
                    if (!empty($input->id_presensi_ekskul)) {
                        $presensi_ekskul = PresensiEkskul::find($input->id_presensi_ekskul);
                    } else {
                        $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $presensi_ekskul = new PresensiEkskul;
                        $presensi_ekskul->id_presensi_ekskul = $id;
                        $presensi_ekskul->id_semester = $input->id_semester;
                        $presensi_ekskul->id_ekskul = $input->id_ekskul;
                    }
                    $presensi_ekskul->pertemuan_ke = $input->pertemuan_ke;
                    $presensi_ekskul->materi_ekskul = $input->materi_ekskul;
                    $presensi_ekskul->waktu_mulai = '00:00';
                    $presensi_ekskul->waktu_selesai = '00:00';
                    $presensi_ekskul->tgl_entry = $tgl_entry;

                    
                    if(!empty(request()->file)){
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;

                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/ekstra/'. $input->id_ekskul .'/'. $id, request()->file, 'public');
                        $presensi_ekskul->image  = $file;
                    }

                    $presensi_ekskul->save();

                    $total_siswa = 0;
                    $total_siswa_masuk = 0;

                    $array_combine = array();

                    foreach ($input->id_siswa as $id => $id_siswa) {
                        $array_combine[] = (object) array(
                            'alasan'  => $input->alasan[$id],
                            'id_kelas' => $input->id_kelas[$id],
                            'id_siswa'    => $input->id_siswa[$id],
                        );
                    }

                    // presensi_ekskul_peserta
                    foreach ($array_combine as $item) {
                        if (!empty($item->alasan)) {
                            $kehadiran = $item->alasan;
                        } else {
                            $kehadiran = 1;
                        }

                        if ($presensi_ekskul_peserta = PresensiEkskulPeserta::where('id_presensi_ekskul', '=', $presensi_ekskul->id_presensi_ekskul)->where('id_siswa', '=', $item->id_siswa)->first()) {
                            $presensi_ekskul_peserta->updated_by                = $input->auth_data->pengguna->id_pengguna;
                        } else {
                            // make id
                            $id_presensi_ekskul_peserta = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                            $presensi_ekskul_peserta                                = new PresensiEkskulPeserta;
                            $presensi_ekskul_peserta->id_presensi_ekskul            = $presensi_ekskul->id_presensi_ekskul;
                            $presensi_ekskul_peserta->id_presensi_ekskul_peserta    = $id_presensi_ekskul_peserta;
                            $presensi_ekskul_peserta->created_by                    = $input->auth_data->pengguna->id_pengguna;
                        }

                        $presensi_ekskul_peserta->id_siswa                    = $item->id_siswa;
                        $presensi_ekskul_peserta->id_kelas                    = $item->id_kelas;
                        $presensi_ekskul_peserta->kehadiran                   = $kehadiran;
                        $presensi_ekskul_peserta->alasan                      = '-';
                        $presensi_ekskul_peserta->save();

                        if ($kehadiran == 1) {
                            $total_siswa++;
                            $total_siswa_masuk++;
                        } else {
                            $total_siswa++;
                        }
                    }

                    $presensi_ekskul->persentase_presensi_ekskul = ($total_siswa_masuk / $total_siswa);
                    $presensi_ekskul->save();

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'absensi-ekskul/input-absensi-ekskul/' . $input->id_semester . '/' . $input->id_ekskul,
                        'message' => 'Save Absensi Ekskul Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                    DebugBar::info($e->getMessage());
                    return [
                        'status' => 300, // GAGAL
                        'message' => 'Absensi Ekskul Gagal!'
                    ];
                }
            } elseif ($mode == 'delete') {
                DB::beginTransaction();
                try {
                    $presensi_ekskul = PresensiEkskul::where('id_presensi_ekskul', $id)->delete();
                    $presensi_ekskul_peserta = PresensiEkskulPeserta::where('id_presensi_ekskul', $id)->delete();

                    DB::commit();
                    // all good

                    return [
                        'status' => 200, // SUCCESS AND LOAD DATATABLES
                        'message' => 'Delete Absensi Ekskul Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' => 'Delete Absensi Gagal!'
                    ];
                }
            }
        }
    }

    public function viewExcelInputAbsensiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view(
            'pelatih-ekskul/absensi-ekskul/input-absensi-ekskul/excel-input-absensi-ekskul',
            compact('auth_data', 'id_semester', 'id_ekskul')
        );
    }

    public function downloadExcelInputAbsensiEkskul(Request $request, $id_semester, $id_ekskul, $day, $start_date, $end_date, $jam_mulai, $jam_selesai)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $dates = CarbonPeriod::create($start_date, $end_date);
        $tanggal = [];

        $tanggal = collect($dates)->map(function ($date) use ($day) {
            if ($date->isoWeekday() == $day) {
                return $date->format('d-m-Y');
            }
        })->filter();

        $data_ekskul = Ekskul::find($id_ekskul);
        $data_siswa = PengambilanEkskul::with('siswa.pengguna')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->get();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data['tanggal'] = $tanggal;
        $data['data_siswa'] = $data_siswa;
        $data['data_ekskul'] = $data_ekskul;
        $data['data_semester'] = $data_semester;
        $data['jam_mulai'] = $jam_mulai;
        $data['jam_selesai'] = $jam_selesai;
        return Excel::download(new ExportPresensiEkskul($data), 'Template Presensi Eksul' . $data_ekskul->nm_exskul . '(' . $start_date . ' - ' . $end_date . ').xlsx');
    }

    public function uploadExcelInputAbsensiEkskul(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            try {
                Excel::import(new UploadPresensiEkskul, $request->file('file-excel'));
            } catch (\Exception $e) {
                return [
                    'status'     => 200, // FAILED
                    'message'     => "Gagal Insert"
                ];
            }
            return [
                'status'     => 200, // FAILED
                'message'     => "Upload Sukses"
            ];
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }
}
