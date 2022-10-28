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
use App\Libraries\SumberDaya\LibGuru;
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

    public function viewCetakRaporWaliKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $wali_kelas = WaliKelas::where('is_aktif', 1)->where('id_guru', $guru->id_guru)->first();
        $data_wali_kelas = LibGuru::fetchDataWaliKelas($auth_data, $wali_kelas->id_kelas)->where('is_aktif', 1)->first();

        return view('guru/wali-kelas/cetak-rapor/view-cetak-rapor', compact('wali_kelas', 'data_wali_kelas'));
    }

    public function datatablesCetakRapor(Request $request)
    {
        set_time_limit(1800);
        // ini_set('max_execution_time', 300);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Kelas::with('jurusan')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $list_rapor_sisipan = RaporSisipan::all();
        // $kurikulum = Kurikulum::where('is_aktif',1)->orderBy('tahun_kurikulum', 'DESC')->get();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->get();

        return Datatables::of($list_data)
            ->addColumn('wali_kelas', function ($item) use ($wali_kelas) {
                $k = $wali_kelas->firstWhere('id_kelas', $item->id_kelas);
                return $k->guru->pengguna->nm_pengguna ?? '';
            })
            ->addColumn('rapor_sisipan', function ($item) use ($list_rapor_sisipan) {
                // $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count();
                return $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count();;
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


    public function addSetting(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::get()->unique('id_mata_pelajaran');
        // dd($rapor_sisipan);
        $mapel = MataPelajaran::all();
        // foreach($rapor_sisipan as $r){
        //     $m =  $mapel->firstWhere('id_mata_pelajaran', $r->id_mata_pelajaran);
        // dd($m->nm_mata_pelajaran);
        // }
        dd($rapor_sisipan);

        return view('akademik/rapor-sisipan/cetak-rapor/add-setting-cetak-rapor', compact('auth_data', 'rapor_sisipan'));
    }


    public function printCetakRapor(Request $request, $id_kelas)
    {
        set_time_limit(1800);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
        // $list_siswa = Siswa::where('id_kelas', $id_kelas)->get();
        // $kurikulum = Kurikulum::where('is_aktif',1)->orderBy('tahun_kurikulum', 'DESC')->with('mapel.mata_pelajaran.jenis_mata_pelajaran')->get();
        // $k = $kurikulum->firstWhere('id_jurusan', $kelas->jurusan->id_jurusan);
        $k = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran')->get();

        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();

        // $rapor_sisipan = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->get();

        $list_komponen = KomponenNilaiRaporSisipan::where('status',1)->where('type','!=','uas')->get();
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('nm_status_pengguna', '=', 'AKTIF');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            })
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();
            // dd($list_nilai);
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman') {
            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor', compact('auth_data', 'kelas', 'list_siswa', 'k', 'list_nilai', 'wali_kelas'));
        } else {
           $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];

                            $nilai_sumatif1 = $list_komponen->firstWhere('urutan',1);
                            $nilai_sumatif2 = $list_komponen->firstWhere('urutan',2);
                            $sts = $list_komponen->where('type','uts')->where('urutan',9)->first();
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . 'uts'] =  $nilaiRapor['nilai'];
                            }
                    }
                }
            }


            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor2', compact('auth_data', 'kelas', 'list_siswa', 'k', 'list_nilai', 'wali_kelas','nilai_siswa','list_komponen','nilai_komponen'));
        }
    }



}
