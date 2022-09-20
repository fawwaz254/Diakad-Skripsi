<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\RaporSisipan;
use App\Models\Siswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Session;
use Validator;


class InputNilaiRaporSisipanController extends Controller
{

    public function viewKomponenInputNilai(Request $request, $id_rapor_sisipan)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan',$id_rapor_sisipan)->with('mata_pelajaran','kelas')->first();
        // dd($rapor_sisipan);
        $list_data = KomponenNilaiRaporSisipan::whereIn('urutan', [1, 2, 5, 6, 9])->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->get();

        $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })
            ->whereHas('komponen_nilai', function ($query) {
                $query->whereIn('urutan', [1, 2, 5, 6, 9]);
            })->get();

        $nilai_siswa = [];
        $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                // dd($nilaiRapor);
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                    $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                    $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                    $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                    if($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai){
                        $nilai_komponen[$nilaiRapor['id_siswa'].'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                    }
                    if($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai){
                        $nilai_komponen[$nilaiRapor['id_siswa'].'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                    }
                    if($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai){
                        $nilai_komponen[$nilaiRapor['id_siswa'].'sts'] =  $nilaiRapor['nilai'];
                    }

                }
            }
        }
        // dd($nilai_komponen);

        return view('guru/rapor-sisipan/daftar-nilai-sts/input-nilai-sts', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa','nilai_komponen','rapor_sisipan'));
    }

    // public function datatablesKomponenNilaiMagang(Request $request, $id_periode_magang)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $nilai_siswa = NilaiMagang::select(
    //         'nilai_magang.id_nilai_magang',
    //         'nilai_magang.id_komponen_magang',
    //         'nilai_magang.besar_nilai_magang',
    //         'komponen_magang.nm_komponen_magang',
    //         'periode_magang.nm_periode_magang',
    //         'siswa.nis_siswa',
    //         'pengguna.nm_pengguna',
    //         'semester.nm_semester',
    //         'semester.tahun_ajaran'
    //     )
    //         ->join('komponen_magang', 'komponen_magang.id_komponen_magang', '=', 'nilai_magang.id_komponen_magang')
    //         ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'komponen_magang.id_periode_magang')
    //         ->join('pengambilan_magang', 'pengambilan_magang.id_periode_magang', '=', 'periode_magang.id_periode_magang')
    //         ->join('siswa', 'siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')
    //         ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
    //         ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
    //         ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang)->get();

    //     $list_siswa = LibMagangSiswa::fetchDataPengajuanSiswaMagangDetailPeriode($auth_data, $id_periode_magang);

    //     return Datatables::of($list_siswa)
    //         ->addColumn('siswa', function ($item) {
    //             return $item->nis_siswa . ' - ' . $item->nm_pengguna;
    //         })
    //         ->addColumn('nm_rekanan_magang', function ($item) {
    //             return $item->nm_rekanan_magang;
    //         })
    //         ->addColumn('semester', function ($item) {
    //             return $item->nm_semester . ' - ' . $item->tahun_ajaran;
    //         })
    //         ->addColumn('nilai_magang_komponen', function ($item) {
    //             return $item->nm_komponen_magang;
    //         })
    //         ->addColumn('action', function ($item) {
    //             $data = array(
    //                 'id' => $item->nis_siswa
    //             );
    //             return $data;
    //         })
    //         ->make(true);
    // }

    public function actionInputNilai(Request $request, $mode, $id_rapor_sisipan = null)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        // $now = Carbon::now(env('APP_TIMEZONE', ''));
        // dd($input->nilai);
        // foreach($input->nilai as $n){
        //     dd($n);

        // }
        // ACTION SAVE
        if ($mode == 'save') {
            // $input_array = (array) $input;

            $input = (object) $request->input();
            $auth_data = $input->auth_data;

            $rapor_sisipan = RaporSisipan::find($id_rapor_sisipan);
            $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->get();

            $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai')
                ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                    $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
                })
                ->whereHas('komponen_nilai', function ($query) {
                    $query->whereIn('urutan', [1, 2, 5, 6, 9]);
                })->get();

            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {

                    foreach ($nilaiRapor as $a) {

                        $nilai = $input->nilai[$nilaiRapor['id_komponen_nilai'] . '-' . $nilaiRapor['id_siswa'] . '-' . $nilaiRapor['id_rapor_sisipan']];
                        $NilaiRaporSisipan                            = NilaiRaporSisipan::where('id_komponen_nilai', $nilaiRapor['id_komponen_nilai'])->where('id_siswa', $nilaiRapor['id_siswa'])->where('id_rapor_sisipan', $nilaiRapor['id_rapor_sisipan'])->first();
                        if ($NilaiRaporSisipan) {
                            $NilaiRaporSisipan->nilai                 = $nilai;
                            $NilaiRaporSisipan->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $NilaiRaporSisipan->save();
                        }
                    }
                }
            }
            return [
                'status' => 202,
                'message' => 'Save successfully',
                'path' => 'rapor-sisipan/daftar-nilai-sts/nilai/' . $id_rapor_sisipan
            ];
        }
    }
}






