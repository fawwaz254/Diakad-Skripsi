<?php

namespace App\Http\Controllers\BK\AktivitasSiswa;

use Carbon\Carbon;
use App\Models\Semester;
use Carbon\CarbonPeriod;
use App\Models\RewardSiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\DataImportExcel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AktivitasRewardSiswa;
use App\Models\JenisAktivitasReward;
use Maatwebsite\Excel\Facades\Excel;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibSiswa;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AktivitasRewardSiswaController extends Controller
{
    public function viewAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $list_data = AktivitasRewardSiswa::with('jenisAktivitasReward')->where('is_aktif', '=', 1);

        return view('bk/aktivitas-siswa/view-aktivitas-reward-siswa', compact('list_data'));
    }

    public function datatablesAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($request->ajax()) {
            $data = AktivitasRewardSiswa::with('jenisAktivitasReward')
                ->select(['id_aktivitas_reward_siswa', 'id_jenis_aktivitas_reward', 'nm_aktivitas_reward_siswa', 'is_aktif', 'nilai_aktivitas']);

            return DataTables::of($data)
                ->addColumn('jenis_aktivitas', function ($row) {
                    return $row->jenisAktivitasReward ? $row->jenisAktivitasReward->nm_jenis_aktivitas_reward : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_aktif == 1 ? 'Aktif' : 'Tidak Aktif';
                })
                ->addColumn('action', function ($row) {
                    $data = array(
                        'id_aktivitas_reward_siswa' => $row->id_aktivitas_reward_siswa
                    );
                    return $data;
                })
                ->make(true);
        }
    }

    public function addAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_aktivitas = JenisAktivitasReward::all();

        return view('bk/aktivitas-siswa/view-add-aktivitas-reward-siswa', compact('data_jenis_aktivitas'));
    }

    public function saveAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validatedData = $request->validate([
            'jenis_aktivitas_reward'    => 'required',
            'nm_aktivitas_reward'       => 'required',
            'nilai_aktivitas'           => 'required',
            'nilai_karakter'            => 'required',
            'status'                    => 'required',
        ], [
            'jenis_aktivitas_reward.required' => 'Jenis aktivitas reward harus diisi.',
            'nm_aktivitas_reward.required'    => 'Nama aktivitas reward harus diisi.',
            'nilai_aktivitas.required'        => 'Nilai aktivitas harus diisi.',
            'nilai_karakter.required'         => 'Nilai karakter harus diisi.',
            'nilai_karakter'                  => 'Nilai karakter harus diisi',
            'status.required'                 => 'Status harus diisi.',
        ]);

        $aktivitas_reward_siswa                             = new AktivitasRewardSiswa();
        $aktivitas_reward_siswa->id_jenis_aktivitas_reward  = $input->jenis_aktivitas_reward;
        $aktivitas_reward_siswa->nm_aktivitas_reward_siswa  = $input->nm_aktivitas_reward;
        $aktivitas_reward_siswa->nilai_aktivitas            = $input->nilai_aktivitas;
        $aktivitas_reward_siswa->nilai_karakter             = $input->nilai_karakter;
        $aktivitas_reward_siswa->is_guru                    = $input->is_guru;
        $aktivitas_reward_siswa->is_siswa                   = $input->is_siswa;
        $aktivitas_reward_siswa->is_aktif                   = $input->status;
        $aktivitas_reward_siswa->created_by                 = $input->auth_data->pengguna->id_pengguna;
        $aktivitas_reward_siswa->save();

        return response()->json([
            'status'    => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'reward-siswa/aktivitas-reward-siswa',
            'message'   => 'Upload Data Successfully'
        ]);
    }

    public function downloadTemplate()
    {
        $fileName = 'template_aktivitas_reward.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Jenis Aktivitas Reward Siswa');
        $sheet->setCellValue('B1', 'Nama Aktivitas Reward Siswa');
        $sheet->setCellValue('C1', 'Nilai Aktivitas');
        $sheet->setCellValue('D1', 'Nilai Karakter');
        $sheet->setCellValue('E1', 'Dinilai oleh Guru');
        $sheet->setCellValue('F1', 'Dinilai oleh Siswa');
        $sheet->setCellValue('A2', 'Harian');
        $sheet->setCellValue('B2', 'Sholat Dhuhur berjamaah');
        $sheet->setCellValue('C2', '1');
        $sheet->setCellValue('D2', 'Disiplin, Religius');
        $sheet->setCellValue('E2', '1');
        $sheet->setCellValue('F2', '1');
        $sheet->setCellValue('A3', 'Insidentil');
        $sheet->setCellValue('B3', 'Upacara 17 Agustus');
        $sheet->setCellValue('C3', '5');
        $sheet->setCellValue('D3', 'Disiplin');
        $sheet->setCellValue('E3', '1');
        $sheet->setCellValue('F3', '0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="template_aktivitas_reward.xlsx"',
            ]
        );
    }

    public function uploadAktivitas(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        try {
            $data_excel = Excel::toArray(new DataImportExcel, $request->file('file'));
        } catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {
            return [
                'status'    => 300, // FAILED
                'message'   => "File yang diupload tidak valid atau tidak dapat dibaca."
            ];
        }

        $worksheet1 = $data_excel[0];

        // dd($worksheet1);

        if (count($worksheet1)) {
            DB::beginTransaction();
            try {
                foreach ($worksheet1 as $key => $row_excel) {
                    $row_excel = (object) $row_excel;
                    // dd($row_excel);
                    $ok = true;
                    if (!$row_excel->jenis_aktivitas_reward_siswa) {
                        $ok &= false;
                    }
                    if (!$row_excel->nama_aktivitas_reward_siswa) {
                        $ok &= false;
                    }
                    if (!$row_excel->nilai_aktivitas) {
                        $ok &= false;
                    }
                    if (!$row_excel->nilai_karakter) {
                        $ok &= false;
                    }
                    // if (!$row_excel->dinilai_oleh_guru) {
                    //     $ok &= false;
                    // }
                    // if (!$row_excel->dinilai_oleh_sekretaris) {
                    //     $ok &= false;
                    // }

                    // dd($ok);

                    if ($ok) {
                        // \Log::info('Processing row:', (array) $row_excel);

                        $aktivitas_reward_siswa                             = new AktivitasRewardSiswa();
                        $aktivitas_reward_siswa->id_jenis_aktivitas_reward  = JenisAktivitasReward::cekJenisAktivitas($row_excel->jenis_aktivitas_reward_siswa);
                        $aktivitas_reward_siswa->nm_aktivitas_reward_siswa  = $row_excel->nama_aktivitas_reward_siswa;
                        $aktivitas_reward_siswa->nilai_aktivitas            = $row_excel->nilai_aktivitas;
                        $aktivitas_reward_siswa->is_guru                    = $row_excel->dinilai_oleh_guru;
                        $aktivitas_reward_siswa->is_siswa                   = $row_excel->dinilai_oleh_siswa;

                        $nilai_karakter_save = '';
                        foreach (explode(',', $row_excel->nilai_karakter) as $kk) {
                            $nilai_karakter_save .= rtrim(ltrim($kk)) . '#';
                        }
                        $aktivitas_reward_siswa->nilai_karakter             = rtrim($nilai_karakter_save, '#');
                        $aktivitas_reward_siswa->is_aktif                   = 1;
                        $aktivitas_reward_siswa->created_by                 = $input->auth_data->pengguna->id_pengguna;
                        $aktivitas_reward_siswa->save();
                    }
                }

                DB::commit();
                return [
                    'status'    => 202, // SUCCESS AND LOAD CONTENT
                    'path'      => 'reward-siswa/aktivitas-reward-siswa',
                    'message'   => 'Upload Data Successfully'
                ];
            } catch (\Exception $e) {
                DB::rollback();

                return [
                    'status'    => 203, // GAGAL
                    'message'   => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
                ];
            }
        } else {
            return [
                'status'    => 300, // FAILED
                'message'   => "File Excel Kosong"
            ];
        }
    }

    public function deleteAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        // dd($input);
        $auth_data = $input->auth_data;

        $data = AktivitasRewardSiswa::find($input->id);
        if ($data) {
            $data->delete();

            return response()->json([
                'status'  => 200,
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }

    public function editAktivitasRewardSiswa($id_aktivitas_reward_siswa)
    {
        $aktifitas_reward = AktivitasRewardSiswa::find($id_aktivitas_reward_siswa);
        $data_jenis_aktivitas = JenisAktivitasReward::all();

        $arrKarakter = explode('#', $aktifitas_reward->nilai_karakter);
        $dataKarakter = json_encode($arrKarakter);

        return view('bk/aktivitas-siswa/view-edit-aktivitas-reward-siswa', compact(['data_jenis_aktivitas', 'aktifitas_reward', 'dataKarakter']));
    }

    public function updateAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validatedData = $request->validate([
            'jenis_aktivitas_reward'    => 'required',
            'nm_aktivitas_reward_siswa' => 'required',
            'nilai_aktivitas'           => 'required',
            'nilai_karakter'            => 'required',
            'status'                    => 'required',
        ], [
            'jenis_aktivitas_reward.required'       => 'Jenis aktivitas reward harus diisi.',
            'nm_aktivitas_reward_siswa.required'    => 'Nama aktivitas reward harus diisi.',
            'nilai_aktivitas.required'              => 'Nilai aktivitas harus diisi.',
            'nilai_karakter.required'               => 'Nilai karakter harus diisi.',
            'nilai_karakter'                        => 'Nilai karakter harus diisi',
            'status.required'                       => 'Status harus diisi.',
        ]);

        $aktivitas_reward_siswa                             = AktivitasRewardSiswa::find($input->id_aktivitas_reward_siswa);
        $aktivitas_reward_siswa->id_jenis_aktivitas_reward  = $input->jenis_aktivitas_reward;
        $aktivitas_reward_siswa->nm_aktivitas_reward_siswa  = $input->nm_aktivitas_reward_siswa;
        $aktivitas_reward_siswa->nilai_aktivitas            = $input->nilai_aktivitas;
        $aktivitas_reward_siswa->nilai_karakter             = $input->nilai_karakter;
        $aktivitas_reward_siswa->is_guru                    = $input->is_guru;
        $aktivitas_reward_siswa->is_sekretaris              = $input->is_sekretaris;
        $aktivitas_reward_siswa->is_aktif                   = $input->status;
        $aktivitas_reward_siswa->created_by                 = $input->auth_data->pengguna->id_pengguna;
        $aktivitas_reward_siswa->update();

        return response()->json([
            'status' => 200,
            'path'      => 'reward-siswa/aktivitas-reward-siswa',
            'message' => 'Data Berhasil di Update.'
        ]);
    }

    public function viewRekapRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::now();
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_aktivitas_reward_siswa = AktivitasRewardSiswa::select('nilai_karakter')->where('is_aktif', 1)->groupBy('nilai_karakter')->get();
        $data_aktivitas_reward_siswa_presensi = ['Komunikasi', 'Kolaborasi', 'Berpikir Kritis', 'Kreatif'];
        $data_semester = Semester::get();
        $data_jenis_aktivitas = JenisAktivitasReward::get();

        if (!empty($input->kelas) && !empty($input->semester)) {
            $semester = Semester::where('id_semester', $input->semester)->first();
            if ($semester->nm_semester == 'Ganjil') {
                $year = Str::before($semester->tahun_ajaran, '/');
                $startOfMonth = Carbon::create($year, 7, 1)->startOfMonth();
                $endOfMonth = Carbon::create($year, 12, 1)->endOfMonth();
            } else {
                // Genap
                $year = Str::after($semester->tahun_ajaran, '/');
                $startOfMonth = Carbon::create($year, 1, 1)->startOfMonth();
                $endOfMonth = Carbon::create($year, 6, 1)->endOfMonth();
            }
            $months = CarbonPeriod::create($startOfMonth, $endOfMonth);

            $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $input->kelas);
            $reward = DB::table('reward_siswa as rs')
                ->select(
                    'rs.id_siswa',
                    'rs.id_event',
                    'rs.id_kelas',
                    'rs.model_event',
                    'ars.nilai_karakter',
                    'ars.id_jenis_aktivitas_reward',
                    'ars.nilai_aktivitas',
                    DB::raw('COUNT(ars.nilai_aktivitas) AS point'),
                )
                ->leftJoin('aktivitas_reward_siswa as ars', 'rs.id_event', '=', 'ars.id_aktivitas_reward_siswa')
                ->where('rs.id_kelas', $input->kelas)
                ->whereIn('rs.id_siswa', $data_siswa->pluck('id_siswa'))
                ->whereBetween('rs.created_at', [$startOfMonth, $endOfMonth])
                ->whereNull('rs.deleted_at')
                ->groupBy('rs.id_siswa', 'rs.id_event', 'rs.id_kelas', 'rs.model_event', 'ars.nilai_aktivitas', 'ars.nilai_karakter')->get();

            $data_presensi = DB::table('reward_siswa as rs')
                ->select(
                    'rs.id_siswa', // id_siswa reward_siswa berisi id_kelas
                    'rs.id_kelas', // id_kelas reward_siswa berisi id_siswa
                    'rs.model_event',
                    'rs.nm_reward_siswa',
                    'pm.id_kelas_mp',
                    'pm.id_presensi_mp',
                    'km.created_at',
                )
                ->leftJoin('presensi_mp AS pm', 'pm.id_presensi_mp', '=', 'rs.id_event')
                ->leftJoin('kelas_mp AS km', 'pm.id_kelas_mp', '=', 'km.id_kelas_mp')
                ->where('km.id_kelas', $input->kelas)
                ->whereIn('rs.nm_reward_siswa', $data_aktivitas_reward_siswa_presensi)
                ->whereIn('rs.id_kelas', $data_siswa->pluck('id_siswa'))
                ->whereNull('rs.deleted_at')
                ->whereNull('pm.deleted_at')
                ->whereNull('km.deleted_at')
                ->whereBetween('km.created_at', [$startOfMonth, $endOfMonth])->get();

            $data_reward = $reward->map(function ($item) {
                $daily = $item->id_jenis_aktivitas_reward == 1 ? floor($item->point / 15) : 0;
                $week = $item->id_jenis_aktivitas_reward == 2 ? floor($item->point / 3) : 0;
                $month = $item->id_jenis_aktivitas_reward == 3 ? floor($item->point / 1) : 0;

                $item->daily_point = $daily;
                $item->week_point = $week;
                $item->month_point = $month;
                $item->total_point = $daily + $week + $month;
                return $item;
            });

            return view('bk.aktivitas-siswa.view-rekap-reward-siswa', compact('data_kelas', 'data_aktivitas_reward_siswa', 'data_aktivitas_reward_siswa_presensi', 'data_jenis_aktivitas', 'data_semester', 'data_siswa', 'data_reward', 'data_presensi', 'months', 'startOfMonth', 'endOfMonth'));
        } else {
            return view('bk.aktivitas-siswa.view-rekap-reward-siswa', compact('data_kelas', 'data_aktivitas_reward_siswa', 'data_jenis_aktivitas', 'data_semester'));
        }
    }

    public function viewApproveRewardSiswa(Request $request)
    {
        // dd('masuk sini');
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // dd($input);

        return view('bk.aktivitas-siswa.view-approve-reward-siswa');
    }

    public function datatableApprovePestasi(Request $request)
    {
        // dd('masuk datatables');
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($request->ajax()) {
            $data = RewardSiswa::select([
                'kelas.nm_kelas as Kelas',
                'pengguna.nm_pengguna as Nama',
                'jenis_aktivitas_reward.nm_jenis_aktivitas_reward as Jenis_Aktivitas',
                'aktivitas_reward_siswa.nm_aktivitas_reward_siswa as Aktivitas_Reward',
                'reward_siswa.created_at',
                'reward_siswa.nm_reward_siswa'
            ])
                ->leftJoin('siswa', 'reward_siswa.id_siswa', '=', 'siswa.id_siswa')
                ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
                ->leftJoin('aktivitas_reward_siswa', 'reward_siswa.id_event', '=', 'aktivitas_reward_siswa.id_aktivitas_reward_siswa')
                ->leftJoin('jenis_aktivitas_reward', 'jenis_aktivitas_reward.id_jenis_aktivitas_reward', '=', 'aktivitas_reward_siswa.id_jenis_aktivitas_reward')
                ->where('reward_siswa.is_aproved', 0)
                ->where('reward_siswa.model_event', 'Siswa - NonKBM')
                ->get();

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal_pengisian', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d');
                })
                ->make(true);
        }
    }

    public function viewInputCapaianKarakter(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        // dd($data_kelas);

        $data_jenis_aktivitas = JenisAktivitasReward::join('aktivitas_reward_siswa ', 'jenis_aktivitas_reward.id_jenis_aktivitas_reward', '=', 'aktivitas_reward_siswa.id_jenis_aktivitas_reward')
            ->where('aktivitas_reward_siswa.is_guru', 1)
            ->select('jenis_aktivitas_reward.*', 'aktivitas_reward_siswa.*')
            ->get();
        dd($data_jenis_aktivitas);

        return view('bk.aktivitas-siswa.view-input-capaian-karakter', compact(['data_kelas', 'data_aktivitas']));
    }
}
