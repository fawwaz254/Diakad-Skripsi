<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

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

class RaporSisipanController extends Controller
{

    public function viewDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/rapor-sisipan/daftar-nilai-sts/view-daftar-nilai-sts', compact('auth_data'));
    }

    public function datatablesDaftarNilaiSTS(Request $request)
    {
        set_time_limit(1800);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = RaporSisipan::with('pengguna', 'mata_pelajaran', 'kelas', 'semester')->get();
        // $belumTerisi = NilaiRaporSisipan::where('nilai', 0)->with('siswa.kelas')->get();
        // ->whereHas('siswa.kelas', function ($query) use ($item) {
        //     $query->where('id_kelas', '=', $item->kelas->id_kelas);
        // })
        //
        // $allSiswa =  Siswa::all();
        // where('id_kelas', $item->kelas->id_kelas)->count();

        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->mata_pelajaran->nm_mata_pelajaran;
            })
            ->addColumn('jumlah', function ($item) {
                //semua siswa

                // $allSiswa = $allSiswa->where('id_kelas', $item->kelas->id_kelas)->count();
                // // $belumTerisi = $belumTerisi->where('siswa.kelas.id_kelas',$item->kelas->id_kelas )->get();
                // // $belumTerisi = $belumTerisi->groupBy('id_siswa')
                // // ->selectRaw('count(*) as total, id_siswa')
                // // ->toArray();
                // //cari siswa yang ada nilai 0 nya
                // $belumTerisi = NilaiRaporSisipan::where('nilai', 0)->with('siswa.kelas')->whereHas('siswa.kelas', function ($query) use ($item) {
                //     $query->where('id_kelas', '=', $item->kelas->id_kelas);
                // })->groupBy('id_siswa')
                //     ->selectRaw('count(*) as total, id_siswa')
                //     ->get()->toArray();

                // //hitung ada berapa nilai kosongnya
                // $arrayJumlahBelumTerisi = array_count_values(array_column($belumTerisi, 'total'));

                // //loop dan cari nilai kosong yang diatas 5
                // $nilaiSiswaYangKosong = 0;
                // for ($i = 6; $i <= 10; $i++) {
                //     if (isset($arrayJumlahBelumTerisi[$i])) {
                //         $nilaiSiswaYangKosong += $arrayJumlahBelumTerisi[$i];
                //     }
                // }
                $allSiswa = 0;
                $nilaiSiswaYangKosong = 0;

                $data = array(
                    'jumlah_siswa' => $allSiswa,
                    'terisi_siswa' => $allSiswa - $nilaiSiswaYangKosong,
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


    public function printDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

        $list_data = KomponenNilaiRaporSisipan::whereIn('urutan', [1, 2, 5, 6, 9])->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
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
                    if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                    }
                    if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                        $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                    }
                }
            }
        }

        $data['nilai_siswa'] = $nilai_siswa;
        $data['nilai_komponen'] = $nilai_komponen;
        $data['rapor_sisipan'] = $rapor_sisipan;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_data;
        $data['id_rapor_sisipan'] = $id_rapor_sisipan;

        return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS.xlsx');
    }

    public function pdfDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman' ||$auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm2' ) {
            $nilai_siswa = [];
            // $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';

                        // $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                        // $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                        // $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                        // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        // }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-with-kkm', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
        } else {

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
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        }
    }
}
