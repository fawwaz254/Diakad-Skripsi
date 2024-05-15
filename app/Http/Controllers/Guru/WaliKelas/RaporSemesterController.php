<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use App\Models\KelompokPribadiSisipan;
use App\Models\KelompokTambahanRapor;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class RaporSemesterController extends Controller
{

    public function viewRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first() ?? NULL;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = !empty($guru) ? LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester) : NULL;
        // $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();
        $kelompok_tambahan_rapor = KelompokTambahanRapor::with('tambahan_rapor')->get();
        $id_semester = $semester_aktif->id_semester;
        $id_kelas = $wali_kelas->id_kelas ?? '';
        return view('guru/wali-kelas/rapor-semester/view-rapor-semester', compact('auth_data', 'id_semester', 'id_kelas', 'kelompok_tambahan_rapor'));
    }

    public function datatablesRaporSemester(Request $request, $id_semester, $id_kelas)
    {
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with(
            [
                'nilai_tambahan_rapor' => function ($q) use ($id_semester) {
                    $q->where('id_semester', $id_semester);
                },
                'pengguna'
            ]
        );

        $kelompok_tambahan_rapor = KelompokTambahanRapor::with('tambahan_rapor')->get();

        return Datatables::of($list_siswa)
            ->addColumn('tambahan_rapor', function ($item) use ($kelompok_tambahan_rapor) {
                $nilai = [];
                foreach ($kelompok_tambahan_rapor as $k) {
                    foreach ($k->tambahan_rapor as $tambahan_rapor) {
                        $cek = $item->nilai_tambahan_rapor->firstWhere('id_tambahan_rapor', $tambahan_rapor->id_tambahan_rapor);
                        if ($cek) {
                            if ($k->nm_kelompok_tambahan_rapor == 'Catatan Untuk Orang Tua') {
                                $nilai[$k->urutan][] =  $cek->nilai;
                            } else {
                                $nilai[$k->urutan][] = $tambahan_rapor->nm_tambahan_rapor . ' : ' . $cek->nilai;
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

    public function  imporExcelDataTambahan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('guru/wali-kelas/rapor-semester/view-import-excel-tambahan-data', compact('auth_data'));
    }

    // public function uploadExcelDataTambahan(Request $request)
    // {
    //     if ($request->hasFile('file-excel')) {
    //         try {
    //             Excel::import(new UploadTambahanRapor, $request->file('file-excel'));
    //         } catch (\Exception $e) {
    //             return [
    //                 'status'     => 200, // FAILED
    //                 'message'     => "Gagal, Cek kembali apakah ada data nilai yang melebihi batas"
    //             ];
    //         }
    //         return [
    //             'status'     => 200, // FAILED
    //             'message'     => "Upload Sukses"
    //         ];
    //     } else {
    //         return [
    //             'status'     => 300, // FAILED
    //             'message'     => "File Excel tidak ditemukan"
    //         ];
    //     }
    // }
}
