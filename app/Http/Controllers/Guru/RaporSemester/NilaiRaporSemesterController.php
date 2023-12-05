<?php

namespace App\Http\Controllers\Guru\RaporSemester;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\RaporSisipan;

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

        $list_data = RaporSisipan::where('id_semester', $id_semester)
            // ->with(['nilai_rapor_sisipan' => function ($q) {
            //     $q->where('nilai', '!=', '0');
            // }, 'pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester'])
            ->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
            ->withCount(['nilai_rapor_sisipan' => function ($q) {
                $q->where('nilai', '!=', 0);
            }])
            ->orderBy('created_at', 'desc');

        // $siswa = Siswa::where('id_kelas', $list_data->pluck('id_kelas'))->whereHas('pengguna.status_pengguna', function ($query) {
        //     $query->where('aktif_status_pengguna', '=', '1');
        // })->get();

        // $komponen = KomponenNilaiRaporSisipan::where('status', '1')->where('type', '!=', 'uas')->count();

        // if ($status == '0') {
        //     $list_data = $list_data->where('id_pengguna', $auth_data->pengguna->id_pengguna);
        // }


        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen) {
                $nilaiLengkap =  $item->kelas->siswa->count() * $komponen;
                $nilaiTerisi = $item->nilai_rapor_sisipan_count;
                if ($nilaiLengkap == '0' || $nilaiTerisi == '0') {
                    $hasil = '0%';
                } else {
                    $hasil = number_format(($nilaiTerisi / $nilaiLengkap) * 100, 2) . '%';
                }

                return $hasil;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })
            ->addColumn('action', function ($item) use ($status) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan,
                    'status' => $status,
                );
                return $data;
            })
            ->make(true);
    }
}
