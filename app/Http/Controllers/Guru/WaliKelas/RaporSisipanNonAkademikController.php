<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use App\Models\KelompokPribadiSisipan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class RaporSisipanNonAkademikController extends Controller
{
    public function viewPengembanganDiri(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();
        $id_semester = $semester_aktif->id_semester;
        $id_kelas = $wali_kelas->id_kelas;
        return view('guru/wali-kelas/rapor-sisipan-input-non-mapel/view-pengembangan-diri', compact('auth_data', 'id_semester', 'id_kelas', 'kelompok_pribadi_sisipan'));
    }

    public function datatablesPengembanganDiri(Request $request, $id_semester, $id_kelas)
    {
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with(
            [
                'nilai_pribadi_sisipan' => function ($q) use ($id_semester) {
                    $q->where('id_semester', $id_semester);
                },
                'pengguna'
            ]
        );

        $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();

        return Datatables::of($list_siswa)
            ->addColumn('pribadi_sisipan', function ($item) use ($kelompok_pribadi_sisipan) {
                $nilai = [];
                foreach ($kelompok_pribadi_sisipan as $k) {

                    foreach ($k->pribadi_sisipan as $pribadi_sisipan) {
                        $cek = $item->nilai_pribadi_sisipan->firstWhere('id_pribadi_sisipan', $pribadi_sisipan->id_pribadi_sisipan);
                        if ($cek) {
                            if ($k->nm_kelompok_pribadi_sisipan == 'Catatan Untuk Orang Tua') {
                                $nilai[$k->urutan][] =  $cek->nilai;
                            } else {
                                $nilai[$k->urutan][] = $pribadi_sisipan->nm_pribadi_sisipan . ' : ' . $cek->nilai;
                            }
                        }
                    }
                }
                $data = array(
                    'n1' => isset($nilai[1]) ? $nilai[1] : null,
                    'n2' => isset($nilai[2]) ? $nilai[2] : null,
                    'n3' => isset($nilai[3]) ? $nilai[3] : null,
                    'n4' => isset($nilai[4]) ? $nilai[4] : null,
                );
                return $data;
            })->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                );
                return $data;
            })
            ->make(true);
    }

    public function imporExcelPengembanganDiri(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('guru/wali-kelas/rapor-sisipan-input-non-mapel/view-import-excel-pengembangan-diri', compact('auth_data'));
    }
}
