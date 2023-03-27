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


class InputNilaiRaporSisipanAkhirController extends Controller
{
    public function viewKomponenInputNilai(Request $request,  $id_rapor_sisipan)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();
        // dd($rapor_sisipan);
        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan')->get();
        // $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })
            ->orderBy('nis_siswa')
            ->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1);
            })->whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', $id_rapor_sisipan);
            })->get();

        $nilai_siswa = [];
        $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                // dd($nilaiRapor);
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                    // $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                    // $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                    // $nilai_sumatif3 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 3');
                    // $nilai_sumatif4 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 4');
                    // $sas = $list_data->firstWhere('nm_nilai', '=', 'SAS');

                    // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_nilai']  == $sas->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
                    // }
                }
            }
        }

        return view('guru/rapor-sisipan/daftar-nilai-sas/input-nilai-sas', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
    }

    public function actionInputNilai(Request $request, $mode, $id_rapor_sisipan = null)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($mode == 'save') {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;

            $rapor_sisipan = RaporSisipan::find($id_rapor_sisipan);
            // $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->get();
            $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')
                ->whereHas('pengguna.status_pengguna', function ($query) {
                    $query->where('aktif_status_pengguna', '=', '1');
                })
                ->orderBy('nis_siswa')
                ->get();

            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
                ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                    $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
                })->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1);;
                })->whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                    $query->where('id_rapor_sisipan', $id_rapor_sisipan);
                })->get();


            $nilaiRaporSisipans = NilaiRaporSisipan::whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', $id_rapor_sisipan);
            })->get();

            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {

                    foreach ($nilaiRapor as $a) {
                        if (isset($input->nilai[$nilaiRapor['id_komponen_nilai'] . '-' . $nilaiRapor['id_siswa'] . '-' . $nilaiRapor['id_rapor_sisipan']])) {
                            $nilai = $input->nilai[$nilaiRapor['id_komponen_nilai'] . '-' . $nilaiRapor['id_siswa'] . '-' . $nilaiRapor['id_rapor_sisipan']];
                            $NilaiRaporSisipan                            = $nilaiRaporSisipans->where('id_komponen_nilai', $nilaiRapor['id_komponen_nilai'])->where('id_siswa', $nilaiRapor['id_siswa'])->where('id_rapor_sisipan', $nilaiRapor['id_rapor_sisipan'])->first();
                            if ($NilaiRaporSisipan) {
                                if (is_numeric($nilai)) {
                                    $NilaiRaporSisipan->nilai                 = $nilai;
                                    $NilaiRaporSisipan->updated_by            = $input->auth_data->pengguna->id_pengguna;
                                    $NilaiRaporSisipan->save();
                                }
                            }
                        }
                    }
                }
            }
            return [
                'status' => 202,
                'message' => 'Save Successfully',
                'path' => 'rapor-sisipan/daftar-nilai-sas/nilai/' . $id_rapor_sisipan
            ];
        }
    }
}
