<?php

namespace App\Http\Controllers\Akademik\KPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\PointKPI;
use App\Models\PredikatKPI;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Yajra\Datatables\Datatables;

class CetakKPIController extends Controller
{
    public function viewCetakKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/kpi/cetak-kpi/view-cetak-kpi', compact('auth_data'));
    }

    public function datatablesViewCetakKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $point_kpi = PointKPI::where('id_semester', $data_semester_aktif->id_semester)->where('jenis', '1')->get();
        $point_mengaji_kpi = PointKPI::where('id_semester', $data_semester_aktif->id_semester)->where('jenis', '3')->get()->count();
        $predikat_kpi = PredikatKPI::whereIn('id_point_kpi', $point_kpi->pluck('id_point_kpi'))->get();
        $list_wali_kelas = WaliKelas::where('id_semester', $data_semester_aktif->id_semester)->where('is_aktif', '1')->with('kelas.siswa', 'guru.pengguna');

        return Datatables::of($list_wali_kelas)->addColumn('terisi', function ($item) use ($point_kpi, $predikat_kpi, $point_mengaji_kpi) {
            $totalPointKPI = ($point_kpi->where('tingkat_kelas', $item->kelas->tingkat)->count() + $point_mengaji_kpi) * $item->kelas->siswa->count();
            $totalPredikatKPI = $predikat_kpi->where('id_kelas', $item->id_kelas)->count();

            if ($totalPointKPI == '0' || $totalPredikatKPI == '0') {
                return '0%';
            }
            return ($totalPredikatKPI / $totalPointKPI * 100) . '%';
        })->addColumn('action', function ($item) {
            $data = array(
                'id'        => $item->id_kelas,
            );
            return $data;
        })->make(true);
    }
    public function detailCetakKPI(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        return view('akademik/kpi/cetak-kpi/view-detail-cetak-kpi', compact('auth_data', 'id_kelas'));
    }

    public function datatablesKelompokKPI(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = Siswa::where('id_kelas', $id_kelas)->with('predikat_kpi', 'pengguna')
            ->whereHas('predikat_kpi.point_kpi', function ($query) use ($semester_aktif) {
                $query->where('id_semester', $semester_aktif->id_semester);
            });
        $kelas = Kelas::find($id_kelas);
        $jumlah_point = PointKPI::where('tingkat_kelas', $kelas->tingkat)->where('id_semester', $semester_aktif->id_semester)->where('jenis', 1)->count() + PointKPI::where('id_semester', $semester_aktif->id_semester)->where('jenis', 3)->count();

        return Datatables::of($list_data)->addColumn('jumlah_point', function () use ($jumlah_point) {
            return $jumlah_point;
        })->addColumn('jumlah_point_terisi', function ($item) {
            if ($item->predikat_kpi) {
                return $item->predikat_kpi->count();
            } else {
                return '0';
            }
        })->addColumn('action', function ($item) use ($semester_aktif) {
            $data = array(
                'id' => $item->id_siswa,
                'id_semester' => $semester_aktif->id_semester
            );
            return $data;
        })->make(true);
    }
}
