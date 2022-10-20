<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Libraries\Pendidikan\LibKelas;
use App\Models\Kurikulum;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Auth;
use DB;
use Session;
use Validator;


class CetakRaporController extends Controller
{
    public function viewCetakRapor(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/rapor-sisipan/cetak-rapor/view-cetak-rapor', compact('auth_data'));
    }

    public function datatablesCetakRapor(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Kelas::with('jurusan')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $kurikulum = Kurikulum::orderBy('tahun_kurikulum', 'DESC')->get();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif',1)->get();

        return Datatables::of($list_data)
            ->addColumn('wali_kelas', function ($item) use($wali_kelas){
                $k = $wali_kelas->firstWhere('id_kelas', $item->id_kelas );
                return $k->guru->pengguna->nm_pengguna ?? '';
            })
            ->addColumn('kurikulum', function ($item) use($kurikulum) {
                $k = $kurikulum->firstWhere('id_jurusan', $item->id_jurusan );
                return $k->nm_kurikulum;
            })
            ->addColumn('action', function ($item) {
                // $k = $kurikulum->firstWhere('id_jurusan', $item->id_jurusan );
                $data = array(
                    'id_kelas'     => $item->id_kelas
                );
                return $data;
            })
            ->make(true);
    }


    public function printCetakRapor(Request $request, $id_kelas){
        set_time_limit(1800);

            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $kelas = Kelas::where('id_kelas',$id_kelas)->with('jurusan')->first();
            // $list_siswa = Siswa::where('id_kelas', $id_kelas)->get();
            $kurikulum = Kurikulum::orderBy('tahun_kurikulum', 'DESC')->with('mapel.mata_pelajaran.jenis_mata_pelajaran')->get();
            $k = $kurikulum->firstWhere('id_jurusan', $kelas->jurusan->id_jurusan);
            $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif',1)->where('id_kelas',$id_kelas)->first();


        // $rapor_sisipan = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->get();

        // $list_data = KomponenNilaiRaporSisipan::where('status',1)->where('type','!=','uas')->get();
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai','rapor_sisipan.semester')
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            })
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status',1)->where('type','!=','uas');
            })->get();
            if($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman'){
                // $nilai_siswa = [];
                // // $nilai_komponen = [];
                // if ($list_siswa) {
                //     $nilai = $list_nilai->toArray();
                //     foreach ($nilai as $nilaiRapor) {
                //         foreach ($nilaiRapor as $a) {
                //             $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                //             // $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'].'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';

                //                 // $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                //                 // $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                //                 // $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                //                 // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                //                 // }
                //                 // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                //                 // }
                //                 // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                //                 // }
                //         }
                //     }
                // }
                // foreach($list_nilai as $a){
                //     dd($a);
                // }
                // dd($list_nilai);
                            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor', compact('auth_data','kelas','list_siswa','k','list_nilai','wali_kelas'));

    }}

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

    // public function pdfDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    // {

    //     set_time_limit(1800);
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->first();

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
    // }

    //     return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
    // }
}
