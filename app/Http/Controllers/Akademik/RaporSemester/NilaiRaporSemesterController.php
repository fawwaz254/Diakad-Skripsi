<?php

namespace App\Http\Controllers\Akademik\RaporSemester;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KelasRapor;
use App\Models\KomponenJenisRapor;
use App\Models\Rapor;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class NilaiRaporSemesterController extends Controller
{
    public function viewNilaiRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/rapor-semester/nilai-rapor/view-nilai-rapor-semester', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesNilaiRaporSemester(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = auth_data();
        $status = $input->status;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = Rapor::where('id_semester', $id_semester)->where('nm_rapor', 'semester')
            ->with('pengguna', 'mata_pelajaran', 'kelas', 'semester')
            ->withCount([
                'nilai_rapor' => function ($q) {
                    $q->where('nilai', '!=', '0');
                },
                // 'kelas.siswa as jumlah_siswa', 'kelas.jenis_rapor.komponen_jenis_rapor as jumlah_komponen_jenis_rapor'
            ])
            ->orderBy('created_at', 'desc');

        $komponen_jenis_rapor = KomponenJenisRapor::get();

        // if ($status == '0') {
        //     $list_data = $list_data->where('created_by', $auth_data->pengguna->id_pengguna);
        // }
        $kelas_rapors = KelasRapor::whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'semester');
        })->get();


        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen_jenis_rapor) {
                $nilaiLengkap =  $item->kelas->loadCount('siswa');

                $nilaiTerisi = $item->nilai_rapor_count;
                $jumlahSiswaKomponen = $nilaiLengkap->siswa_count * $komponen_jenis_rapor->where('id_jenis_rapor', $item->kelas->id_jenis_rapor)->count();
                if ($jumlahSiswaKomponen  == '0' || $nilaiTerisi == '0') {
                    $hasil = '0%';
                } else {
                    $hasil = number_format(($nilaiTerisi /  $jumlahSiswaKomponen) * 100, 2) . '%';
                }

                return $hasil;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })->addColumn('status', function ($item) use ($kelas_rapors) {
                $kelas_rapor = $kelas_rapors->where('id_kelas', $item->id_kelas)->where('mata_pelajaran_rapor.id_mata_pelajaran', $item->id_mata_pelajaran)->first();
                if ($kelas_rapor) {
                    return 'Valid';
                } else {
                    return 'Tidak Valid';
                }
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
