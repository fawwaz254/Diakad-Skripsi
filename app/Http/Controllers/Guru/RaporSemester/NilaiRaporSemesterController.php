<?php

namespace App\Http\Controllers\Guru\RaporSemester;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\KelasRapor;
use App\Models\Rapor;
use App\Models\RaporSisipan;
use DB;

class NilaiRaporSemesterController extends Controller
{
    public function viewNilaiRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/rapor-semester/view-nilai-rapor-semester', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesNilaiRaporSemester(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $status = $input->status;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = Rapor::where('id_semester', $id_semester)
            ->with('pengguna', 'mata_pelajaran', 'kelas', 'semester')
            ->withCount([
                'nilai_rapor' => function ($q) {
                    $q->where('nilai', '!=', 0);
                },
                // 'kelas.siswa as jumlah_siswa', 'kelas.jenis_rapor.komponen_jenis_rapor as jumlah_komponen_jenis_rapor'
            ])
            ->orderBy('created_at', 'desc');


        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) {
                // $nilaiLengkap =  $item->kelas->siswa->count();
                // $nilaiTerisi = $item->nilai_rapor_sisipan_count;
                // if ($nilaiLengkap == '0' || $nilaiTerisi == '0') {
                //     $hasil = '0%';
                // } else {
                //     $hasil = number_format(($nilaiTerisi / $nilaiLengkap) * 100, 2) . '%';
                // }
                $hasil = 0;
                return $hasil;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })
            ->addColumn('action', function ($item) use ($status) {
                $data = array(
                    'id'     => $item->id_rapor,
                    'status' => $status,
                );
                return $data;
            })
            ->make(true);
    }
    public function addNilaiRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $data['list_mapel'] = MataPelajaran::with('jenis_mata_pelajaran')->get();
        $data['list_kelas'] = Kelas::where('is_aktif', 1)->get();
        // $data['list_jurusan'] = Jurusan::all();
        // $data['jenis_mapel'] = JenisMataPelajaran::all();
        $data['semester_aktif'] = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data['data_semester'] = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/rapor-semester/add-nilai-rapor-semester', compact('auth_data'), $data);
    }
    public function getMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $kelas_sisipan = KelasRapor::where('id_kelas', $input->id_kelas)->with('mata_pelajaran_rapor.mata_pelajaran')->get();
        return $kelas_sisipan;
    }

    public function actionsNilaiRaporSemester(Request $request, $mode, $id)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        // $auth_data = $input->auth_data;
        // $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        if ($mode == 'delete') {
            DB::beginTransaction();
            try {
                DB::table('nilai_rapor_sisipan')->where('id_rapor_sisipan', $id)->delete();
                $raporSisipan = RaporSisipan::where('id_rapor_sisipan', $id)->first();
                if ($raporSisipan) {
                    $raporSisipan->delete();
                }

                DB::Commit();
                return [
                    'status' => 202,
                    'path' => 'rapor-sisipan/daftar-nilai-sts',
                    'message' => 'Delete Rapor Sisipan Successfully'
                ];
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                DB::rollback();
                return [
                    'status' => 202,
                    'path' => 'rapor-sisipan/daftar-nilai-sts',
                    'message' => 'Delete Rapor Sisipan Gagal, Silahkan coba lagi'
                ];
            }
        }

        if ($mode == 'add') {
            $cekDuplicate = Rapor::where('id_kelas', $input->id_kelas)->where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('id_semester', $input->id_semester)
                ->with('pengguna')->first();
            $validator = Validator::make($request->all(), [
                'id_mata_pelajaran' => 'required',
                'id_kelas'              => 'required'
            ]);
        }

        if ($cekDuplicate) {
            return [
                'status' => 300, // FAILED
                'message' => 'Kelas dan Mapel Sudah digunakan oleh ' . $cekDuplicate->pengguna->nm_pengguna,
            ];
        }

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $rapor                      = new Rapor;
                    $rapor->id_rapor    = $id;
                    $rapor->id_semester         = $input->id_semester;
                    $rapor->id_mata_pelajaran   = $input->id_mata_pelajaran;
                    $rapor->id_kelas            = $input->id_kelas;
                    $rapor->created_by          = $input->auth_data->pengguna->id_pengguna;
                    $rapor->save();

                    $siswa = Siswa::where('id_kelas', $input->id_kelas)->with('pengguna.status_pengguna')
                        ->whereHas('pengguna.status_pengguna', function ($query) {
                            $query->where('aktif_status_pengguna', '=', '1');
                        })
                        ->get();

                    $komponen_nilai = KomponenNilaiRaporSisipan::where('status', 1)->get();
                    foreach ($siswa as $s) {
                        foreach ($komponen_nilai as $komponen) {
                            $id = $input->auth_data->sekolah_data->prefix . strtotime(Carbon::now(env('APP_TIMEZONE', ''))) . uniqid();
                            $list_data[] = [
                                'id_nilai_rapor_sisipan' =>  $id,
                                // 'id_rapor_sisipan' => $rapor_sisipan->id_rapor_sisipan,
                                'id_komponen_nilai' => $komponen->id_komponen_nilai,
                                'id_siswa' => $s->id_siswa,
                                'nilai' => 0,
                                'created_by' => $input->auth_data->pengguna->id_pengguna,
                            ];
                        }
                    }
                    CreateRaporSisipan::dispatch($list_data);

                    DB::Commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'rapor-sisipan/daftar-nilai-sts',
                        'message' => 'Save Tambah Nilai Successfully'
                    ];
                } catch (\Exception $e) {

                    DB::rollback();

                    return [
                        'status' => 300, // GAGAL
                        'message' => 'Siswa di kelas ini kosong'
                    ];
                }
            }
        }
    }
}
