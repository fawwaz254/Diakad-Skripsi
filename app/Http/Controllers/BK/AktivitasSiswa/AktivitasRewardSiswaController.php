<?php

namespace App\Http\Controllers\BK\AktivitasSiswa;

use App\Http\Controllers\Controller;
use App\Imports\DataImportExcel;
use App\Models\AktivitasRewardSiswa;
use App\Models\JenisAktivitasReward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $aktivitas_reward_siswa->is_sekretaris              = $input->is_sekretaris;
        $aktivitas_reward_siswa->is_aktif                   = $input->status;
        $aktivitas_reward_siswa->created_by                 = $input->auth_data->pengguna->id_pengguna;
        $aktivitas_reward_siswa->save();

        return response()->json([
            'status'    => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'aktivitas-siswa/aktivitas-reward-siswa',
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
        $sheet->setCellValue('F1', 'Dinilai oleh Sekretaris');
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
                        $aktivitas_reward_siswa->is_sekretaris              = $row_excel->dinilai_oleh_sekretaris;

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
                    'path'      => 'aktivitas-siswa/aktivitas-reward-siswa',
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
            'path'      => 'aktivitas-siswa/aktivitas-reward-siswa',
            'message' => 'Data Berhasil di Update.'
        ]);
    }
}
