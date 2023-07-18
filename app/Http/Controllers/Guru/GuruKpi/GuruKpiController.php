<?php

namespace App\Http\Controllers\Guru\GuruKpi;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Semester;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use App\Http\Controllers\Controller;
use App\Models\KategoriKpi;
use App\Models\Kelas;
use App\Models\Kpi;
use App\Models\Siswa;
use Validator;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class GuruKpiController extends BaseController
{
    public function viewIndexKpi(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester = Semester::get();
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return (view('guru/guru-kpi/rekap-nilai-kpi/index',compact('semester','kelas','auth_data')));
    }

    public function RekapNilaiKpiSiswa(Request $request,$id_siswa){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kpi = KategoriKpi::get();
        $siswa = Siswa::where('id_siswa',$id_siswa)->first();
        $semester = Semester::where('is_aktif_semester',1)->first();

        // $siswa = Siswa::select('siswa.id_siswa', 'pengguna.id_pengguna', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
        //     ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
        //     ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
        //     ->where('siswa.id_wali_murid', '=', $id_wali_murid)
        //     ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        $teskpi = KategoriKpi::join('subkategori_kpi','subkategori_kpi.id_kategori_kpi', '=', 'kategori_kpi.id_kategori_kpi')->get();
        // dd($teskpi);

        return (view('guru/guru-kpi/rekap-nilai-kpi/rekapnilai',compact('kpi','auth_data','siswa','semester')));
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

    public function viewIndexKpiDetail(Request $request, $kelas, $semester){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester = $semester;
        $semester = Semester::where('id_semester',$data_semester)->first();
        $data_kelas = LibKelas::fetchDataKelas($auth_data, $kelas);

        // $list_data = LibSiswa::fetchDataSiswa($auth_data, $kelas, null, 'only-aktif');
        // dd($list_data);
        return (view('guru/guru-kpi/rekap-nilai-kpi/indexdetail',compact('semester','data_kelas','kelas','auth_data')));
    }

    public function datatablesRekapNilaiKpiSiswa(Request $request, $id_kelas){
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

}
