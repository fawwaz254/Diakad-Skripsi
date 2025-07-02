<?php

namespace App\Http\Controllers\PPDB\Peserta;

use App\Exports\ExportPenetapan;
use App\Http\Controllers\SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController;
use App\Models\Jurusan;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Mpdf\Mpdf;
use Yajra\Datatables\Datatables;

use App\Models\CalonSiswaBaru as CalonSiswaBaru;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;
use App\Models\Kelas;
use App\Models\Penerimaan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImportExcel;
use App\Models\Admisi;
use App\Models\CalonSiswaSekolah;
use App\Models\Jalur;
use App\Models\JalurSiswa;
use App\Models\Kota;
use App\Models\Pengguna;
use App\Models\Provinsi;
use App\Models\RolePengguna;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\StatusPengguna;
use Auth;
use DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use PhpOffice\PhpWord\Settings;
use Ramsey\Uuid\Uuid;
use Session;
use Validator;

class ProsesPenetapanController extends BaseController
{
    /**
     * View page awal proses penetapan, show list data penerimaan
     * @param Request
     * @return View
     */
    // public function viewProsesPenetapan(Request $request)
    // {
    //     $input      = (object) $request->input();
    //     $auth_data  = auth_data();

    //     /** get all data penerimaan */

    //     $currentYear = date("Y");

    //     $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

    //     /** groupping by year and semester */
    //     $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
    //         return $item->groupBy('nm_semester_penerimaan');
    //     });

    //     $mode = 'view';

    //     return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun', 'mode'));
    // }


    public function viewProsesPenetapan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        /** Tentukan semester aktif berdasarkan bulan saat ini */
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if ($currentMonth >= 1 && $currentMonth <= 6) {
            $activeSemester = 'Ganjil';
            $previousSemester = ['Genap', $currentYear - 1]; // Semester sebelumnya (Genap tahun lalu)
            $nextSemester = ['Genap', $currentYear]; // Semester berikutnya (Genap tahun ini)
        } else {
            $activeSemester = 'Genap';
            $previousSemester = ['Ganjil', $currentYear]; // Semester sebelumnya (Ganjil tahun ini)
            $nextSemester = ['Ganjil', $currentYear + 1]; // Semester berikutnya (Ganjil tahun depan)
        }

        /** Ambil data untuk semester sebelumnya, sekarang, dan berikutnya */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data)
            ->filter(function ($item) use ($activeSemester, $currentYear, $previousSemester, $nextSemester) {
                return ($item->nm_semester_penerimaan == $activeSemester && $item->tahun_penerimaan == $currentYear) ||
                    ($item->nm_semester_penerimaan == $previousSemester[0] && $item->tahun_penerimaan == $previousSemester[1]) ||
                    ($item->nm_semester_penerimaan == $nextSemester[0] && $item->tahun_penerimaan == $nextSemester[1]);
            });

        // dd($penerimaan);

        $mode = 'view';

        return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'penerimaan', 'mode'));
    }



    /**
     * Action post view for editing proses penetapan
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewProsesPenetapan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        $validator  = Validator::make($request->all(), [
            'id_penerimaan' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            return [
                'status'    => 204, // SUCCESS AND LOAD CONTENT
                'path'      => 'peserta/proses-penetapan/' . $input->id_penerimaan
            ];
        }
    }

    /**
     * View detail proses penetapan, to show list calon siswa at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function showPeserta($id, Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        /** get all data penerimaan */
        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $data_penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        /** get penerimaan by id */
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if ($currentMonth >= 1 && $currentMonth <= 6) {
            $activeSemester = 'Ganjil';
            $previousSemester = ['Genap', $currentYear - 1]; // Semester sebelumnya (Genap tahun lalu)
            $nextSemester = ['Genap', $currentYear]; // Semester berikutnya (Genap tahun ini)
        } else {
            $activeSemester = 'Genap';
            $previousSemester = ['Ganjil', $currentYear]; // Semester sebelumnya (Ganjil tahun ini)
            $nextSemester = ['Ganjil', $currentYear + 1]; // Semester berikutnya (Ganjil tahun depan)
        }

        /** Ambil data untuk semester sebelumnya, sekarang, dan berikutnya */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data)
            ->filter(function ($item) use ($activeSemester, $currentYear, $previousSemester, $nextSemester) {
                return ($item->nm_semester_penerimaan == $activeSemester && $item->tahun_penerimaan == $currentYear) ||
                    ($item->nm_semester_penerimaan == $previousSemester[0] && $item->tahun_penerimaan == $previousSemester[1]) ||
                    ($item->nm_semester_penerimaan == $nextSemester[0] && $item->tahun_penerimaan == $nextSemester[1]);
            });



        $showPenerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) {
            abort(404);
        }

        $mode = 'show';

        return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'showPenerimaan', 'penerimaan', 'mode', 'id'));
    }

    public function viewInputCalonSiswa($id, Request $request)
    {
        $data_provinsi = Provinsi::all();
        $data_jurusan = Jurusan::all();
        return view('ppdb/peserta/proses-penetapan/view-input-calon-siswa', compact('id', 'data_provinsi', 'data_jurusan'));
    }

    public function datatablesProsesPenetapan($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = LibPenerimaan::fetchDataCalonSiswaPenetapan($auth_data, $id);

        return Datatables::of($list_data)
            ->addColumn('is_siswa', function ($item) {
                $is_siswa = Siswa::where('id_c_siswa', $item->id_c_siswa)->first();
                return $is_siswa ? 'Sudah Jadi Siswa' : 'Belum Jadi Siswa';
            })
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id' => $item->id_c_siswa,
                );
                return $data;
            })
            ->make(true);
    }

    public function actionPenetapan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validator = Validator::make($request->all(), []);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            DB::beginTransaction();

            try {
                foreach ($input->id_c_siswa as $id_c_siswa) {
                    $c_siswa                = CalonSiswaBaru::find($id_c_siswa);
                    $c_siswa->nomor_ujian   = 'U-' . $c_siswa->kode_voucher;
                    $c_siswa->updated_at    = $now;
                    $c_siswa->updated_by    = auth_data()->pengguna->id_pengguna;
                    $c_siswa->save();
                }
                DB::commit();
                return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'message' => 'Penetapan Berhasil',
                    'path' => 'peserta/proses-penetapan/' . $input->id_penerimaan
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong

                return [
                    'status' => 203, // GAGAL
                    'message' => 'Proses Penetapan Gagal'
                ];
            }
        }
    }
    public function excelPenetapan(Request $request, $id_penerimaan)
    {
        $penerimaan = Penerimaan::find($id_penerimaan);
        $data = CalonSiswaBaru::where('id_penerimaan', $id_penerimaan)->whereNotNull('kode_voucher')->with('siswa.kelas')
            ->with('calon_siswa_ortu.jenis_pendidikan_ayah', 'calon_siswa_ortu.jenis_pekerjaan_ayah', 'calon_siswa_ortu.jenis_penghasilan_ayah', 'calon_siswa_ortu.jenis_pendidikan_ibu', 'calon_siswa_ortu.jenis_pekerjaan_ibu', 'calon_siswa_ortu.jenis_penghasilan_ibu', 'calon_siswa_ortu.jenis_pekerjaan_wali', 'kota_lahir', 'agama', 'provinsi', 'kota', 'calon_siswa_sekolah.kota_asal_sekolah')->get();

        $nm_penerimaan = str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $penerimaan->nm_penerimaan);
        return Excel::download(new ExportPenetapan($data), 'Data Siswa Penetapan (' . $nm_penerimaan . ' - ' . $penerimaan->gelombang_penerimaan . ').xlsx');
    }

    public function uploadPenetapan(Request $request, $id_penerimaan)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kelas_paling_rendah = Kelas::orderBy('tingkat')->first();
        $kelas = Kelas::where('tingkat', $kelas_paling_rendah->tingkat)->get();
        return view('ppdb/peserta/proses-penetapan/view-upload-penetapan', compact('auth_data', 'id_penerimaan', 'kelas'));
    }

    public function uploadPenetapanCalonSiswa(Request $request, $id_penerimaan)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kelas_paling_rendah = Kelas::orderBy('tingkat')->first();
        $kelas = Kelas::where('tingkat', $kelas_paling_rendah->tingkat)->get();
        return view('ppdb/peserta/proses-penetapan/view-upload-penetapan-calon-siswa', compact('auth_data', 'id_penerimaan', 'kelas'));
    }

    public function postUploadPenetapan(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = auth_data();

        if ($request->hasFile('file-excel')) {
            $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
            $data = $data[0];

            if (count($data)) {
                $status_join_table = StatusPengguna::where('status_join_table', '3')->where('nm_status_pengguna', 'AKTIF')->first();
                $sekolah = Sekolah::first();
                $semester = Semester::where('is_aktif_semester', '1')->first();
                $jalur = Jalur::where('nm_jalur', 'REGULER')->first();

                foreach ($data as  $data_row) {
                    if (empty($data_row['nis'])) {
                        continue;
                    }

                    $calon_siswa_baru = CalonSiswaBaru::where('kode_voucher', $data_row['kode_voucher'])->first();
                    if (empty($calon_siswa_baru)) {
                        return [
                            'status'    => 300, // FAILED
                            'message'   => 'Upload Data Siswa Gagal' . $data_row['kode_voucher'] . ' tidak ditemukan di dalam sistem'
                        ];
                    }

                    if ($data_row['nis'] == '(isi manual)') {
                        return [
                            'status'    => 300, // FAILED
                            'message'   => 'Upload Data Siswa Gagal, Harap Isi NIS terlebih dahulu'
                        ];
                    }

                    if (empty($data_row['nama_kelas']) || $data_row['nama_kelas'] == '(isi manual)') {
                        return [
                            'status'    => 300, // FAILED
                            'message'   => 'Upload Data Siswa Gagal, Harap Isi Nama Kelas terlebih dahulu'
                        ];
                    }

                    $kelas = Kelas::where('nm_kelas', $data_row['nama_kelas'])->first();
                    if (empty($kelas)) {
                        return [
                            'status'    => 300, // FAILED
                            'message'   => 'Upload Data Siswa Gagal,' . $data_row['nama_kelas'] . 'tidak ditemukan'
                        ];
                    }
                    $siswa = Siswa::where('nis_siswa', $data_row['nis'])->first();

                    if ($siswa) {
                        // continue;
                        $pengguna = Pengguna::where('id_pengguna', $siswa->id_pengguna)->first();
                    } else {
                        $pengguna = null;
                    }
                    $now = Carbon::now();

                    if (empty($pengguna)) {
                        //buat pengguna
                        $pengguna = new Pengguna;
                        $pengguna->id_pengguna = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                        $updateData = false;
                    } else {
                        $updateData = true;
                    }

                    $pengguna->id_status_pengguna = $status_join_table->id_status_pengguna;
                    $pengguna->id_sekolah = $sekolah->id_sekolah;
                    $pengguna->nm_pengguna = $calon_siswa_baru->nm_c_siswa;
                    $pengguna->username = $data_row['nis'];
                    $pengguna->password = Hash::make($data_row['nis']);
                    $pengguna->must_change_password = '1';
                    $pengguna->status_join_table = '3';
                    $pengguna->created_by = 'Penetapan';
                    $pengguna->save();

                    if (empty($siswa)) {
                        $siswa = new Siswa;
                        $siswa->id_siswa = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                    }
                    //buat siswa
                    $siswa->id_pengguna =  $pengguna->id_pengguna;
                    $siswa->id_c_siswa = $calon_siswa_baru->id_c_siswa;
                    $siswa->id_kelompok_biaya = null;
                    $siswa->id_kelas = $kelas->id_kelas;
                    $siswa->id_wali_murid = null;
                    $siswa->is_aktif_wali_murid = '1';
                    $siswa->is_orang_tua = null;
                    $siswa->nis_siswa = $data_row['nis'];
                    $siswa->nisn_siswa = null;
                    $siswa->thn_masuk_siswa = $semester->thn_akademik_semester;
                    $siswa->created_by = 'Penetapan';
                    $siswa->save();

                    if ($updateData) {
                    } else {
                        $role_pengguna = new RolePengguna;
                        // $role_pengguna->id_role_pengguna = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                        $role_pengguna->id_role = '3';
                        $role_pengguna->id_pengguna = $pengguna->id_pengguna;
                        $role_pengguna->keterangan_role_pengguna = 'Input Pendidikan';
                        $role_pengguna->is_aktif = '1';
                        $role_pengguna->created_by = "Penetapan";
                        $role_pengguna->save();

                        //buat admisi
                        $admisi = new Admisi;
                        $admisi->id_admisi = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                        $admisi->id_siswa  =  $siswa->id_siswa;
                        $admisi->id_semester = $semester->id_semester;
                        $admisi->id_status_pengguna = $status_join_table->id_status_pengguna;
                        $admisi->id_jalur = $jalur->id_jalur;
                        $admisi->created_by = 'Penetapan';
                        $admisi->save();

                        //buat jalur_siswa
                        $jalur_siswa = new JalurSiswa;
                        $jalur_siswa->id_jalur_siswa = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                        $jalur_siswa->id_siswa = $siswa->id_siswa;
                        $jalur_siswa->id_jalur = $jalur->id_jalur;
                        $jalur_siswa->id_semester = $semester->id_semester;
                        $jalur_siswa->is_jalur_aktif = '1';
                        $jalur_siswa->id_admisi = $admisi->id_admisi;
                        $jalur_siswa->created_by = 'Penetapan';
                        $jalur_siswa->save();
                    }
                    //buat role pengguna

                }
                return [
                    'status'    => 300, // FAILED
                    'message'   =>  'Save Siswa Successfully'
                ];
            }
        }
    }

    public function downloadFileExcel()
    {
        $file = public_path() . "/excel/ContohFileExcelUploadDataCalonSiswa.xlsx";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohFileExcelUploadDataCalonSiswa.xlsx', $headers);
    }

    public function cekFileExcel(Request $request)
    {
        session()->forget('data_excel_siswa');
        if ($request->hasFile('file-excel')) {
            $datas = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
            $datas = $datas[0];
            if (count($datas)) {
                session(['data_excel_siswa' => $datas]);

                return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'siswa/upload-data-siswa/cek-data-siswa'
                ];
            }
        }
    }

    public function cetakKuitansi($id_siswa)
    {
        // // Pastikan folder kuitansi/temp ada
        // Settings::setTempDir(public_path('word/temp'));

        // // Ambil data siswa berdasarkan ID
        // $siswa = CalonSiswaBaru::where('id_c_siswa', $id_siswa)->first();
        // $kota = Kota::where('id_kota', $siswa->alamat_kota)->first();
        // $provinsi = Provinsi::where('id_provinsi', $siswa->alamat_provinsi)->first();
        // if (!$siswa) {
        //     return back()->with('error', 'Data siswa tidak ditemukan.');
        // }

        // // dd($siswa);

        // // Cek apakah file template ada
        // $templatePath = public_path('word/template-kuitansi.docx');
        // if (!file_exists($templatePath)) {
        //     return back()->with('error', 'File template tidak ditemukan.');
        // }

        // // Load template Word
        // $templateProcessor = new TemplateProcessor($templatePath);

        // // Isi template dengan data siswa
        // $templateProcessor->setValue('nama_siswa', $siswa->nm_c_siswa);
        // $templateProcessor->setValue('sekolah_asal', $siswa->asal_sekolah);
        // $templateProcessor->setValue('alamat_rumah', $siswa->alamat_jalan . ", " . $siswa->alamat_dusun . " RT " . $siswa->alamat_rt . "/" . "RW " . $siswa->alamat_rw . " " . $siswa->alamat_kelurahan . ", Kecamatan " . $siswa->alamat_kecamatan . ", " . $kota->nm_kota . ", " . $provinsi->nm_provinsi . ".");
        // $templateProcessor->setValue('nomor_telepon', $siswa->nomor_hp);

        // $jurusanIds = [
        //     $siswa->id_pilihan_jurusan_1,
        //     $siswa->id_pilihan_jurusan_2,
        //     $siswa->id_pilihan_jurusan_3
        // ];

        // $jurusanList = Jurusan::whereIn('id_jurusan', $jurusanIds)->get()->keyBy('id_jurusan');

        // $templateProcessor->setValue('jurusan_1', $jurusanList->get($siswa->id_pilihan_jurusan_1)->kode_jurusan ?? '');
        // $templateProcessor->setValue('jurusan_2', $jurusanList->get($siswa->id_pilihan_jurusan_2)->kode_jurusan ?? '');
        // $templateProcessor->setValue('jurusan_3', $jurusanList->get($siswa->id_pilihan_jurusan_3)->kode_jurusan ?? '');
        // $templateProcessor->setValue('tanggal_pembuatan', now()->format('d-m-Y'));
        // $templateProcessor->setValue('nomor_pendaftaran', substr($siswa->kode_voucher, -3));

        // $outputFile = public_path('word/kuitansi-' . $siswa->id_c_siswa . '.docx');
        // $templateProcessor->saveAs($outputFile);

        // // Berikan file ke user untuk didownload
        // return response()->download($outputFile)->deleteFileAfterSend(true);

        $siswa = CalonSiswaBaru::find($id_siswa);
        $kota = Kota::where('id_kota', $siswa->alamat_kota)->first();
        $provinsi = Provinsi::where('id_provinsi', $siswa->alamat_provinsi)->first();

        $alamat = $siswa->alamat_jalan . ", " . $siswa->alamat_dusun . " RT " . $siswa->alamat_rt . "/" . "RW " . $siswa->alamat_rw . " " . $siswa->alamat_kelurahan . ", Kecamatan " . $siswa->alamat_kecamatan . ", " . $kota->nm_kota . ", " . $provinsi->nm_provinsi . ".";
        $tanggal = date('d-m-Y');
        $nomer_pendaftaran = substr($siswa->kode_voucher, -3);

        $jurusan_1 = Jurusan::where('id_jurusan', $siswa->id_pilihan_jurusan_1)->first();
        $jurusan_2 = Jurusan::where('id_jurusan', $siswa->id_pilihan_jurusan_2)->first();
        $jurusan_3 = Jurusan::where('id_jurusan', $siswa->id_pilihan_jurusan_3)->first();

        $jurusan_1 = $jurusan_1 ? $jurusan_1->kode_jurusan : '';
        $jurusan_2 = $jurusan_2 ? $jurusan_2->kode_jurusan : '';
        $jurusan_3 = $jurusan_3 ? $jurusan_3->kode_jurusan : '';

        return view('ppdb/peserta/proses-penetapan/cetak-kuitansi', compact('id_siswa', 'siswa', 'alamat', 'tanggal', 'nomer_pendaftaran', 'jurusan_1', 'jurusan_2', 'jurusan_3'));
    }

    public function getKota($id_provinsi)
    {
        $kota = Kota::where('id_provinsi', $id_provinsi)->get();

        return response()->json($kota);
    }

    public function storeDataCalonSiswa(Request $request, $id)
    {
        $id = (string) $id; // Pastikan ID adalah string

        // Validasi request
        $data = $request->validate([
            'nm_c_siswa' => 'required',
            'nik_siswa' => 'required',
            'jenis_kelamin' => 'required',
            'asal_sekolah' => 'required',
            'nomor_hp' => 'required',
            'alamat_provinsi' => 'required',
            'alamat_kota' => 'required',
            'alamat_kecamatan' => 'required',
            'alamat_kelurahan' => 'required',
            'alamat_dusun' => 'required',
            'alamat_rt' => 'required',
            'alamat_rw' => 'required',
            'alamat_jalan' => 'required',
            'alamat_kodepos' => 'required',
            'id_pilihan_jurusan_1' => 'required',
            'id_pilihan_jurusan_2' => 'required',
            'id_pilihan_jurusan_3' => 'required',
        ]);

        // Cek apakah calon siswa sudah terdaftar berdasarkan NIK
        $calon_siswa_baru = CalonSiswaBaru::where('nik_siswa', $data['nik_siswa'])->first();

        if ($calon_siswa_baru) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa dengan NIK ' . $data['nik_siswa'] . " sudah terdaftar!",
            ], 401);
        }

        // Cari satu voucher yang belum digunakan
        $voucher = DB::table('voucher')
            ->leftJoin('calon_siswa_baru', 'voucher.kode_voucher', '=', 'calon_siswa_baru.kode_voucher')
            ->where('voucher.id_penerimaan', $id)
            ->whereNull('calon_siswa_baru.kode_voucher')
            ->select('voucher.kode_voucher') // Ambil hanya kode_voucher
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak tersedia!',
            ], 404);
        }



        $id_c_siswa = Uuid::uuid4()->toString();
        $id_pengguna = Auth::user()->id_pengguna;
        // Buat calon siswa baru dengan voucher yang tersedia
        CalonSiswaBaru::create([
            "id_c_siswa" => $id_c_siswa,
            "nm_c_siswa" => $data["nm_c_siswa"],
            "nik_siswa" => $data["nik_siswa"],
            "jenis_kelamin" => $data["jenis_kelamin"],
            "asal_sekolah" => $data["asal_sekolah"],
            "nomor_hp" => $data["nomor_hp"],
            "alamat_provinsi" => $data["alamat_provinsi"],
            "alamat_kota" => $data["alamat_kota"],
            "alamat_kecamatan" => $data["alamat_kecamatan"],
            "alamat_kelurahan" => $data["alamat_kelurahan"],
            "alamat_dusun" => $data["alamat_dusun"],
            "alamat_rt" => $data["alamat_rt"],
            "alamat_rw" => $data["alamat_rw"],
            "alamat_jalan" => $data["alamat_jalan"],
            "alamat_kodepos" => $data["alamat_kodepos"],
            "id_pilihan_jurusan_1" => $data["id_pilihan_jurusan_1"],
            "id_pilihan_jurusan_2" => $data["id_pilihan_jurusan_2"],
            "id_pilihan_jurusan_3" => $data["id_pilihan_jurusan_3"],
            "kode_voucher" => $voucher->kode_voucher, // Simpan kode voucher yang dipilih
            "id_penerimaan" => $id, // Pastikan ID penerimaan disimpan
            "created_by" => $id_pengguna,
            "status_verifikasi" => 2,
        ]);

        CalonSiswaSekolah::create([
            "id_c_siswa" => $id_c_siswa,
            "nm_sekolah_asal" => $data["asal_sekolah"],
            "created_by" => $id_pengguna,
        ]);
        return response()->json([
            'success' => true,
            'message' => "Berhasil mendaftarkan calon siswa!",
        ], 201);
    }
}
