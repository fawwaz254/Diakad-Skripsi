<?php

namespace App\Http\Controllers\PPDB\Peserta;

use App\Exports\ExportPenetapan;
use App\Http\Controllers\SaranaPrasarana\PerawatanSarpras\PengadaanSarprasController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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
use App\Models\Jalur;
use App\Models\JalurSiswa;
use App\Models\Pengguna;
use App\Models\RolePengguna;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\StatusPengguna;
use Auth;
use DB;
use Session;
use Validator;

class ProsesPenetapanController extends BaseController
{
    /**
     * View page awal proses penetapan, show list data penerimaan
     * @param Request
     * @return View
     */
    public function viewProsesPenetapan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        $mode = 'view';

        return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun', 'mode'));
    }

    /**
     * Action post view for editing proses penetapan
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewProsesPenetapan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

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
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $data_penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $data_penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) {
            abort(404);
        }

        $mode = 'show';

        return view('ppdb/peserta/proses-penetapan/view-proses-penetapan', compact('auth_data', 'grup_penerimaan_tahun', 'penerimaan', 'mode', 'id'));
    }

    public function datatablesProsesPenetapan($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibPenerimaan::fetchDataCalonSiswaPenetapan($auth_data, $id);

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id' => $item->id_c_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionPenetapan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

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
                    $c_siswa->updated_by    = $input->auth_data->pengguna->id_pengguna;
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
        $data = CalonSiswaBaru::where('id_penerimaan', $id_penerimaan)->with('calon_siswa_ortu.jenis_pendidikan_ayah', 'calon_siswa_ortu.jenis_pekerjaan_ayah', 'calon_siswa_ortu.jenis_penghasilan_ayah', 'calon_siswa_ortu.jenis_pendidikan_ibu', 'calon_siswa_ortu.jenis_pekerjaan_ibu', 'calon_siswa_ortu.jenis_penghasilan_ibu', 'calon_siswa_ortu.jenis_pekerjaan_wali', 'kota_lahir', 'agama', 'provinsi', 'kota', 'calon_siswa_sekolah.kota_asal_sekolah')->get();

        $nm_penerimaan = str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $penerimaan->nm_penerimaan);
        return Excel::download(new ExportPenetapan($data), 'Data Siswa Penetapan (' . $nm_penerimaan . ' - ' . $penerimaan->gelombang_penerimaan . ').xlsx');
    }

    public function uploadPenetapan(Request $request, $id_penerimaan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas_paling_rendah = Kelas::orderBy('tingkat')->first();
        $kelas = Kelas::where('tingkat', $kelas_paling_rendah->tingkat)->get();
        return view('ppdb/peserta/proses-penetapan/view-upload-penetapan', compact('auth_data', 'id_penerimaan', 'kelas'));
    }

    public function postUploadPenetapan(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($request->hasFile('file-excel')) {
            $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
            $data = $data[0];

            if (count($data)) {
                $status_join_table = StatusPengguna::where('status_join_table', '3')->where('nm_status_pengguna', 'AKTIF')->first();
                $sekolah = Sekolah::first();
                $semester = Semester::where('is_aktif_semester', '1')->first();
                $jalur = Jalur::where('nm_jalur', 'REGULER')->first();

                foreach ($data as  $data_row) {
                    $calon_siswa_baru = CalonSiswaBaru::where('kode_voucher', $data_row['kode_voucher'])->first();
                    if (empty($calon_siswa_baru)) {
                        return [
                            'status'    => 300, // FAILED
                            'message'   => 'Upload Data Siswa Gagal' . $data_row['kode_voucher'] . ' tidak ditemukan di dalam sistem'
                        ];
                    }

                    if (empty($data_row['nis']) || $data_row['nis'] == '(isi manual)') {
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
                        continue;
                    }
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    //buat pengguna
                    $pengguna = new Pengguna;
                    $pengguna->id_pengguna = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $pengguna->id_status_pengguna = $status_join_table->id_status_pengguna;
                    $pengguna->id_sekolah = $sekolah->id_sekolah;
                    $pengguna->nm_pengguna = $calon_siswa_baru->nm_c_siswa;
                    $pengguna->username = $data_row['nis'];
                    $pengguna->password = Hash::make($data_row['nis']);
                    $pengguna->must_change_password = '1';
                    $pengguna->status_join_table = '3';
                    $pengguna->created_by = 'Penetapan';
                    $pengguna->save();

                    //buat siswa
                    $siswa = new Siswa;
                    $siswa->id_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
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

                    //buat role pengguna
                    $role_pengguna = new RolePengguna;
                    // $role_pengguna->id_role_pengguna = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $role_pengguna->id_role = '3';
                    $role_pengguna->id_pengguna = $pengguna->id_pengguna;
                    $role_pengguna->keterangan_role_pengguna = 'Input Pendidikan';
                    $role_pengguna->is_aktif = '1';
                    $role_pengguna->created_by = "Penetapan";
                    $role_pengguna->save();

                    //buat admisi
                    $admisi = new Admisi;
                    $admisi->id_admisi = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $admisi->id_siswa  =  $siswa->id_siswa;
                    $admisi->id_semester = $semester->id_semester;
                    $admisi->id_status_pengguna = $status_join_table->id_status_pengguna;
                    $admisi->id_jalur = $jalur->id_jalur;
                    $admisi->created_by = 'Penetapan';
                    $admisi->save();

                    //buat jalur_siswa
                    $jalur_siswa = new JalurSiswa;
                    $jalur_siswa->id_jalur_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $jalur_siswa->id_siswa = $siswa->id_siswa;
                    $jalur_siswa->id_jalur = $jalur->id_jalur;
                    $jalur_siswa->id_semester = $semester->id_semester;
                    $jalur_siswa->is_jalur_aktif = '1';
                    $jalur_siswa->id_admisi = $admisi->id_admisi;
                    $jalur_siswa->created_by = 'Penetapan';
                    $jalur_siswa->save();
                }
                return [
                    'status'    => 300, // FAILED
                    'message'   =>  'Save Siswa Successfully'
                ];
            }
        }
    }
}
