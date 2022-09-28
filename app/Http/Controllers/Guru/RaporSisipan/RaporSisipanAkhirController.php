<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\RaporSisipan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanAkhirController extends Controller
{
    public function viewDaftarNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/rapor-sisipan/daftar-nilai-sas/view-daftar-nilai-sas', compact('auth_data'));
    }

    public function datatablesDaftarNilaiSAS(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = RaporSisipan::with('pengguna', 'mata_pelajaran', 'kelas', 'semester')->where('id_pengguna', $auth_data->pengguna->id_pengguna)->get();

        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->mata_pelajaran->nm_mata_pelajaran;
            })
            ->addColumn('jumlah', function ($item) {
                //semua siswa
                $allSiswa =  Siswa::where('id_kelas', $item->kelas->id_kelas)->count();

                //cari siswa yang ada nilai 0 nya
                $belumTerisi = NilaiRaporSisipan::where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', 0)->with('siswa.kelas')->whereHas('siswa.kelas', function ($query) use ($item) {
                    $query->where('id_kelas', '=', $item->kelas->id_kelas);
                })->groupBy('id_siswa')
                    ->selectRaw('count(*) as total, id_siswa')
                    ->get()->toArray();

                //hitung ada berapa nilai kosongnya
                $arrayJumlahBelumTerisi = array_count_values(array_column($belumTerisi, 'total'));

                //loop dan cari nilai kosong yang diatas 5
                $nilaiSiswaYangKosong = 0;
                $nilaiSiswaYangKosong2 = 0;
                for ($i = 1; $i <= 10; $i++) {
                    if($i >= 6 && $i <= 10){
                        if (isset($arrayJumlahBelumTerisi[$i])) {
                            $nilaiSiswaYangKosong += $arrayJumlahBelumTerisi[$i];
                        }
                    }else{
                        if (isset($arrayJumlahBelumTerisi[$i])) {
                            $nilaiSiswaYangKosong2 += $arrayJumlahBelumTerisi[$i];
                        }
                    }
                }

                $data = array(
                    'jumlah_siswa' => $allSiswa,
                    'terisi_siswa_sts' => $allSiswa - $nilaiSiswaYangKosong,
                    // 'jumlah_siswa_sas' => $allSiswa,
                    'terisi_siswa_sas' => $allSiswa - $nilaiSiswaYangKosong2 - $nilaiSiswaYangKosong
                );
                // dd($data['terisi_siswa']);
                return $data;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan
                );
                return $data;
            })
            ->make(true);
    }


    // public function printDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    // {

    //     set_time_limit(1800);
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

    //     $list_data = KomponenNilaiRaporSisipan::whereIn('urutan', [1, 2, 5, 6, 9])->get();
    //     $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();

    //     $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
    //         ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
    //             $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
    //         })
    //         ->whereHas('komponen_nilai', function ($query) {
    //             $query->whereIn('urutan', [1, 2, 5, 6, 9]);
    //         })->get();

    //     $nilai_siswa = [];
    //     $nilai_komponen = [];
    //     if ($list_siswa) {
    //         $nilai = $list_nilai->toArray();
    //         foreach ($nilai as $nilaiRapor) {
    //             // dd($nilaiRapor);
    //             foreach ($nilaiRapor as $a) {
    //                 $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

    //                 $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
    //                 $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
    //                 $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
    //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
    //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
    //                 }
    //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
    //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
    //                 }
    //                 if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
    //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
    //                 }
    //             }
    //         }
    //     }

    //     $data['nilai_siswa'] = $nilai_siswa;
    //     $data['nilai_komponen'] = $nilai_komponen;
    //     $data['rapor_sisipan'] = $rapor_sisipan;
    //     $data['list_siswa'] = $list_siswa;;
    //     $data['list_data'] = $list_data;
    //     $data['id_rapor_sisipan'] = $id_rapor_sisipan;

    //     return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS.xlsx');
    // }

    public function pdfDaftarNilaiSAS(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->first();

        $list_data = KomponenNilaiRaporSisipan::all();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })->get();


        $nilai_siswa = [];
        $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                    $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                    $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                    $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                    $nilai_sumatif3 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 3');
                    $nilai_sumatif4 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 4');
                    $sas = $list_data->firstWhere('nm_nilai', '=', 'SAS');
                    if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $sas->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
                    }
                }
            }
        }
        return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
    }
}
