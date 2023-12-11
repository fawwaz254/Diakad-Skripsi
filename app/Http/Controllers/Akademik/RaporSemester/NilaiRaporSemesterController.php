<?php

namespace App\Http\Controllers\Akademik\RaporSemester;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Rapor;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class NilaiRaporSemesterController extends Controller
{
    public function viewNilaiRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/rapor-semester/nilai-rapor/view-nilai-rapor-semester', compact('auth_data', 'semester_aktif', 'data_semester'));
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

        // if ($status == '0') {
        //     $list_data = $list_data->where('created_by', $auth_data->pengguna->id_pengguna);
        // }


        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) {
                $nilaiLengkap =  $item->kelas->loadCount('siswa');

                $nilaiTerisi = $item->nilai_rapor_count;
                if ($nilaiLengkap->siswa_count == '0' || $nilaiTerisi == '0') {
                    $hasil = '0%';
                } else {
                    $hasil = number_format(($nilaiTerisi / $nilaiLengkap->siswa_count) * 100, 2) . '%';
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
                );
                return $data;
            })
            ->make(true);
    }
}
