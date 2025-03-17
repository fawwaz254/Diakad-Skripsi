<?php

namespace App\Http\Controllers\Guru\RaporAgama;

use App\Exports\RekapRapor;
use App\Exports\TemplateNilaiRapor;
use App\Http\Controllers\Controller;
use App\Imports\UploadNilaiRaporAgama;
use App\Jobs\CreateNilaiRapor;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\KelasRapor;
use App\Models\KomponenJenisRapor;
use App\Models\NilaiRapor;
use App\Models\Rapor;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
use Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class InputNilaiRaporAgamaController extends Controller
{
    public function viewNilaiRaporAgama(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/rapor-agama/view-nilai-rapor-agama', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesNilaiRaporAgama(Request $request)
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
            ->with('pengguna', 'mata_pelajaran', 'kelas', 'semester')->where('nm_rapor', 'agama')
            ->withCount([
                'nilai_rapor' => function ($q) {
                    $q->where('nilai', '!=', '0');
                },
            ])
            ->orderBy('created_at', 'desc');

        if ($status == '0') {
            $list_data = $list_data->where('created_by', $auth_data->pengguna->id_pengguna);
        }
        $komponen_jenis_rapor = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor',  'agama');
        })->get();

        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen_jenis_rapor) {
                $nilaiLengkap =  $item->kelas->loadCount('siswa');

                $nilaiTerisi = $item->nilai_rapor_count;
                $jumlahSiswaKomponen = $nilaiLengkap->siswa_count * $komponen_jenis_rapor->count();
                if ($jumlahSiswaKomponen == '0' || $nilaiTerisi == '0') {
                    $hasil = '0%';
                } else {
                    $hasil = number_format(($nilaiTerisi / $jumlahSiswaKomponen) * 100, 2) . '%';
                }

                return $hasil;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })
            ->addColumn('action', function ($item) use ($status) {
                $data = array(
                    'id'     => $item->id_rapor,
                    'status' => $status,
                    'id_kelas' => $item->id_kelas,
                    'id_semester' => $item->id_semester,
                );
                return $data;
            })
            ->make(true);
    }
    public function viewTambahNilaiRaporAgama(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data['list_kelas'] = Kelas::where('is_aktif', 1)->get();
        $data['semester_aktif'] = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data['data_semester'] = LibDataAkademik::fetchDataNamaSemester($auth_data);
        return view('guru/rapor-agama/add-nilai-rapor-agama', compact('auth_data'), $data);
    }

    public function getMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $kelas_rapor['mapel'] = KelasRapor::where('id_kelas', $input->id_kelas)->with('mata_pelajaran_rapor.mata_pelajaran')
            ->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
                $q->where('nm_rapor', 'agama');
            })->get();;
        return $kelas_rapor;
    }
    public function actionsNilaiRaporAgama(Request $request, $mode, $id)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        if ($mode == 'delete') {
            DB::beginTransaction();
            try {
                DB::table('nilai_rapor')->where('id_rapor', $id)->delete();
                $rapor = Rapor::where('id_rapor', $id)->first();
                if ($rapor) {
                    $rapor->delete();
                }

                DB::Commit();
                return [
                    'status' => 202,
                    'path' => 'rapor-agama/tambah-nilai-rapor-agama',
                    'message' => 'Delete Rapor Semester Successfully'
                ];
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                DB::rollback();
                return [
                    'status' => 202,
                    'path' => 'rapor-agama/tambah-nilai-rapor-agama',
                    'message' => 'Delete Rapor Semester Gagal, Silahkan coba lagi'
                ];
            }
        }

        if ($mode == 'add') {
            $cekDuplicate = Rapor::where('id_kelas', $input->id_kelas)->where('nm_rapor', 'agama')->where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('id_semester', $input->id_semester)
                ->with('pengguna')->first();
            $validator = Validator::make($request->all(), [
                'id_mata_pelajaran' => 'required',
                'id_kelas'              => 'required'
            ]);
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
            }
        }


        $now = Carbon::now();
        if ($mode == 'add') {
            DB::beginTransaction();

            try {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $rapor                      = new Rapor;
                $rapor->id_rapor            = $id;
                $rapor->id_semester         = $input->id_semester;
                $rapor->id_mata_pelajaran   = $input->id_mata_pelajaran;
                $rapor->id_kelas            = $input->id_kelas;
                $rapor->nm_rapor            = 'agama';

                $rapor->created_by          = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();

                $siswa = Siswa::where('id_kelas', $input->id_kelas)
                    ->whereHas('pengguna.status_pengguna', function ($query) {
                        $query->where('aktif_status_pengguna', '=', '1');
                    })
                    ->get();

                $komponen_jenis_rapor = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor',  'agama');
                })->get();

                foreach ($siswa as $s) {
                    foreach ($komponen_jenis_rapor as $komponen) {
                        $id = $input->auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        $list_data[] = [
                            'id_nilai_rapor' =>  $id,
                            'id_rapor' => $rapor->id_rapor,
                            'id_komponen_jenis_rapor' => $komponen->id_komponen_jenis_rapor,
                            'id_siswa' => $s->id_siswa,
                            'nilai' => 0,
                            'keterangan' => null,
                            'created_at' => Carbon::now(),
                            'created_by' => $input->auth_data->pengguna->id_pengguna,
                        ];
                    }
                }
                CreateNilaiRapor::dispatch($list_data);

                DB::Commit();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-agama/tambah-nilai-rapor-agama',
                    'message' => 'Save Tambah Nilai Successfully'
                ];
            } catch (\Exception $e) {

                DB::rollback();


                return [
                    'status' => 300, // GAGAL
                    'message' => $e->getMessage()
                ];
            }
        } elseif ($mode == 'edit') {

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'rapor-agama/tambah-nilai-rapor-agama',
                'message' => 'Edit Successfully'
            ];
        }
    }
    public function templateExcel(Request $request, $id_rapor)
    {

        set_time_limit(-1);

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor',  'agama');
        })->get();

        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor', function ($query) use ($rapor) {
            $query->where('id_rapor', '=', $rapor->id_rapor);
        })->with(['nilai_rapor' => function ($query) use ($rapor) {
            $query->where('id_rapor', $rapor->id_rapor);
        }])->orderBy('nis_siswa')->get();

        $nilai_siswa = [];
        // if ($list_siswa->nilai_rapor) {

        foreach ($list_siswa as $siswa) {
            $nilai = $siswa->nilai_rapor->toArray();
            foreach ($nilai as $nilaiRapor) {
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor'] . 'nilai'] = $nilaiRapor['nilai'];
                }
            }
        }

        $data['nilai_siswa'] = $nilai_siswa;
        $data['rapor'] = $rapor;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_data;
        $data['id_rapor'] = $id_rapor;
        $nm_mata_pelajaran = str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $rapor->mata_pelajaran->nm_mata_pelajaran);
        return Excel::download(new TemplateNilaiRapor($data), 'Template Excel Rapor Agama (' . $rapor->kelas->nm_kelas . ' - ' . $nm_mata_pelajaran . ').xlsx');
    }

    public function uploadNilaiRapor(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            try {
                Excel::import(new UploadNilaiRaporAgama, $request->file('file-excel'));
            } catch (\Exception $e) {
                return [
                    'status'     => 200, // FAILED
                    'message'     => "Gagal, Cek kembali apakah ada data nilai yang melebihi batas"
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
    public function printRekap(Request $request, $id_rapor)
    {
        // set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();

        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor',  'agama');
        })->get();
        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->get();
        // $keterangan_rapors = KeteranganRapor::where('id_rapor', $rapor->id_rapor)->get();
        $nilai_siswa = [];
        // $list_kd_aktif = [];
        // $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                // foreach ($nilaiRapor as $a) {
                $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor'] . 'nilai'] = $nilaiRapor['nilai'];

                // $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor'] . 'keterangan2'] = $nilaiRapor['keterangan2'];
                // }
            }
        }
        // foreach ($list_data as $key => $data) {
        //     $data1 = $list_nilai->where('id_komponen_jenis_rapor', $data->id_komponen_jenis_rapor)->where('nilai', '!=', 0)->first();
        //     if (!empty($data1)) {
        //         $list_kd_aktif[$key]['id_komponen_jenis_rapor'] =   $data->id_komponen_jenis_rapor;
        //         $list_kd_aktif[$key]['nm_nilai'] =   $data->nm_nilai;
        //     }
        // }



        $data['nilai_siswa'] = $nilai_siswa;
        // $data['nilai_komponen'] = $nilai_komponen;
        $data['rapor'] = $rapor;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_data;
        $data['id_rapor'] = $id_rapor;

        return Excel::download(new RekapRapor($data), 'Rekap Rapor Agama (' . $rapor->kelas->nm_kelas . ' - ' . $rapor->mata_pelajaran->nm_mata_pelajaran . ').xlsx');
    }
}
