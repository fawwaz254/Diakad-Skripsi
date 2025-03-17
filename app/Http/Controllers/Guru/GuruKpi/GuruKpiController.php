<?php

namespace App\Http\Controllers\Guru\GuruKpi;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Semester;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use App\Http\Controllers\Controller;
use App\Models\HasKpi;
use App\Models\KategoriKpi;
use App\Models\Kelas;
use App\Models\Kpi;
use App\Models\NilaiKomponenKpi;
use App\Models\Siswa;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class GuruKpiController extends BaseController
{
    public function viewIndexKpi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester = Semester::get();
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return (view('guru/guru-kpi/rekap-nilai-kpi/index', compact('semester', 'kelas', 'auth_data')));
    }
    public function viewIndexInputKpi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // $semester = Semester::get();
        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return (view('guru/guru-kpi/input-nilai-kpi/index', compact('kelas', 'auth_data')));
    }

    public function RekapNilaiKpiSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kpi = KategoriKpi::get();
        $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        $semester = Semester::where('is_aktif_semester', 1)->first();

        // $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
        //     ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
        //     ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
        //     ->where('siswa.id_wali_murid', '=', $id_wali_murid)
        //     ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        $teskpi = KategoriKpi::join('subkategori_kpi', 'subkategori_kpi.id_kategori_kpi', '=', 'kategori_kpi.id_kategori_kpi')->get();
        // dd($teskpi);

        return (view('guru/guru-kpi/rekap-nilai-kpi/rekapnilai', compact('kpi', 'auth_data', 'siswa', 'semester')));
    }
    public function InputNilaiKpiSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa = Siswa::where('id_siswa', $id_siswa)->first();
        // dd($siswa->kelas->tingkat);
        $semester = Semester::where('is_aktif_semester', 1)->first();
        // dd($semester->nm_semester);
        $kpi = KategoriKpi::where('tingkat', $siswa->kelas->tingkat)->where('semester', $semester->nm_semester)->get();
        // dd($kpi);

        // $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
        //     ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
        //     ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
        //     ->where('siswa.id_wali_murid', '=', $id_wali_murid)
        //     ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        $teskpi = KategoriKpi::join('subkategori_kpi', 'subkategori_kpi.id_kategori_kpi', '=', 'kategori_kpi.id_kategori_kpi')->get();
        // dd($teskpi);

        return (view('guru/guru-kpi/input-nilai-kpi/inputnilai', compact('kpi', 'auth_data', 'siswa', 'semester')));
    }

    public function actionviewIndexKpiDetail(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'semester' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'guru-kpi/rekap-nilai-kpi-detail/' . $input->kelas . '/' . $input->semester
            ];
        }
    }

    public function actionviewIndexinputKpiDetail(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester = Semester::where('is_aktif_semester', 1)->first()->id_semester;
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            // 'semester' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'guru-kpi/input-nilai-kpi-detail/' . $input->kelas . '/' . $semester
            ];
        }
    }

    public function viewIndexKpiDetail(Request $request, $kelas, $semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester = $semester;
        $semester = Semester::where('id_semester', $data_semester)->first();
        $data_kelas = LibKelas::fetchDataKelas($auth_data, $kelas);

        // $list_data = LibSiswa::fetchDataSiswa($auth_data, $kelas, null, 'only-aktif');
        // dd($list_data);
        return (view('guru/guru-kpi/rekap-nilai-kpi/indexdetail', compact('semester', 'data_kelas', 'kelas', 'auth_data')));
    }

    public function viewIndexInputKpiDetail(Request $request, $kelas, $semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester = $semester;
        $semester = Semester::where('id_semester', $data_semester)->first();
        $data_kelas = LibKelas::fetchDataKelas($auth_data, $kelas);

        // $list_data = LibSiswa::fetchDataSiswa($auth_data, $kelas, null, 'only-aktif');
        // dd($list_data);
        return (view('guru/guru-kpi/input-nilai-kpi/indexdetail', compact('semester', 'data_kelas', 'kelas', 'auth_data')));
    }

    public function datatablesRekapNilaiKpiSiswa(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswa($auth_data, $id_kelas);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionInputNilaiKpi(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($input);
        $semester = Semester::where('is_aktif_semester', 1)->first();

        $validator = Validator::make($request->all(), [
            'id_komponen' => 'required',
            'nilai_komponen' => 'required',
        ]);
        $siswa = Siswa::where('id_siswa', $input->id_siswa)->first();
        $kategori = KategoriKpi::where('semester', $semester->nm_semester)->where('tingkat', $siswa->kelas->tingkat)->get();
        // dd($kategori);

        $kpi = Kpi::where('id_siswa', $input->id_siswa)->where('id_semester', $input->id_semester)->first();
        if ($kpi != null) {
            foreach ($input->id_komponen as $key => $value) {
                $now1 = Carbon::now();
                $nilai = NilaiKomponenKpi::where('id_kpi', $kpi->id_kpi)->where('id_komponen', $input->id_komponen[$key])->where('id_siswa', $input->id_siswa)->first();
                $nilai->nilai_komponen = $input->nilai_komponen[$key];
                if ($input->nilai_komponen[$key] == 'A') {
                    $nilai->deskripsi_nilai = 'Sangat mampu';
                } elseif ($input->nilai_komponen[$key] == 'B') {
                    $nilai->deskripsi_nilai = 'Mampu';
                } elseif ($input->nilai_komponen[$key] == 'C') {
                    $nilai->deskripsi_nilai = 'Cukup mampu';
                } elseif ($input->nilai_komponen[$key] == 'D') {
                    $nilai->deskripsi_nilai = 'Kurang mampu';
                }
                $nilai->updated_at = $now1;
                $nilai->save();
            }
        } else {
            $now1 = Carbon::now();

            $kpi = new Kpi;
            $kpi->id_kpi = $input->auth_data->sekolah_data->prefix . strtotime($now1) . uniqid();
            $kpi->id_semester = $input->id_semester;
            $kpi->id_siswa = $input->id_siswa;
            $kpi->id_kelas = $siswa->kelas->id_kelas;
            $kpi->save();


            // $haskpi = new HasKpi;
            foreach ($kategori as $item) {
                $haskpi = new HasKpi;
                $haskpi->id_has_kpi = $input->auth_data->sekolah_data->prefix . strtotime($now1) . uniqid();
                $haskpi->id_kpi = $kpi->id_kpi;
                $haskpi->id_kategori_kpi = $item->id_kategori_kpi;
                $haskpi->created_at = $now1;
                $haskpi->save();
            }

            foreach ($input->id_komponen as $key => $value) {
                $now1 = Carbon::now();
                $nilai = new NilaiKomponenKpi;
                $nilai->id_nilai_kpi = $input->auth_data->sekolah_data->prefix . strtotime($now1) . uniqid();
                $nilai->id_kpi = $kpi->id_kpi;
                $nilai->id_komponen = $input->id_komponen[$key];
                $nilai->id_siswa = $siswa->id_siswa;
                $nilai->nilai_komponen = $input->nilai_komponen[$key];
                if ($input->nilai_komponen[$key] == 'A') {
                    $nilai->deskripsi_nilai = 'Sangat mampu';
                } elseif ($input->nilai_komponen[$key] == 'B') {
                    $nilai->deskripsi_nilai = 'Mampu';
                } elseif ($input->nilai_komponen[$key] == 'C') {
                    $nilai->deskripsi_nilai = 'Cukup mampu';
                } elseif ($input->nilai_komponen[$key] == 'D') {
                    $nilai->deskripsi_nilai = 'Kurang mampu';
                }
                $nilai->updated_at = $now1;
                $nilai->save();
            }
        }
        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'guru-kpi/input-nilai-kpi-detail/' . $siswa->kelas->id_kelas . '/' . $semester->id_semester
            ];
        }
    }
}
