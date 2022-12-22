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
use App\Models\RaporSisipanDeskripsi;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SubRaporSisipan;
use App\Models\UrutanRaporSisipan;
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
                return $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count();
            })
            ->addColumn('action', function ($item)  use ($list_rapor_sisipan) {
                // $k = $kurikulum->firstWhere('id_jurusan', $item->id_jurusan );
                $data = array(
                    'id_kelas'     => $item->id_kelas,
                    'jumlah'        => $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count()
                );
                return $data;
            })
            ->make(true);
    }


    public function viewSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $rapor_sisipan = RaporSisipan::get()->unique('id_mata_pelajaran');
        // dd($rapor_sisipan);

        // foreach($rapor_sisipan as $r){
        //     $m =  $mapel->firstWhere('id_mata_pelajaran', $r->id_mata_pelajaran);
        // dd($m->nm_mata_pelajaran);
        // }
        // dd($mapel);

        return view('akademik/rapor-sisipan/cetak-rapor/view-setting-cetak-rapor', compact('auth_data'));
    }

    public function viewDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-sisipan/cetak-rapor/view-deskripsi-cetak-rapor', compact('auth_data'));
    }

    public function addSetting(Request $request, $mata_pelajaran)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($mata_pelajaran);
        $mapel = MataPelajaran::where('id_mata_pelajaran', $mata_pelajaran)->with('urutan_rapor_sisipan')->first();

        return view('akademik/rapor-sisipan/cetak-rapor/add-setting-cetak-rapor', compact('auth_data', 'mapel'));
    }

    public function postSetting(Request $request, $mata_pelajaran)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($mata_pelajaran);


        if ($urutan_rapor_sisipan = UrutanRaporSisipan::find($mata_pelajaran)) {
            $urutan_rapor_sisipan->urutan               = $input->urutan;
            $urutan_rapor_sisipan->updated_by          = $input->auth_data->pengguna->id_pengguna;
            $urutan_rapor_sisipan->save();
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $urutan_rapor_sisipan               = new UrutanRaporSisipan;
            $urutan_rapor_sisipan->id_urutan_rapor_sisipan    = $id;
            $urutan_rapor_sisipan->urutan               = $input->urutan;
            $urutan_rapor_sisipan->id_mata_pelajaran   = $mata_pelajaran;
            $urutan_rapor_sisipan->created_by          = $input->auth_data->pengguna->id_pengguna;
            $urutan_rapor_sisipan->save();
        }

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'rapor-sisipan/cetak-rapor/viewSetting',
            'message' => 'Save Successfully'
        ];
    }

    public function datatablesViewSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $mapel = MataPelajaran::with('urutan_rapor_sisipan')->get()->sortBy('urutan_rapor_sisipan.urutan');
        return Datatables::of($mapel)
            ->addColumn('urutan', function ($item) {
                return $item->urutan_rapor_sisipan->urutan ?? 'Urutan belum di Set';
            })
            ->addColumn('action', function ($item) {
                // $k = $kurikulum->firstWhere('id_jurusan', $item->id_jurusan );
                $data = array(
                    'id_mata_pelajaran'     => $item->id_mata_pelajaran
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesViewDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $deskripsi = RaporSisipanDeskripsi::get()->sortBy('tingkat');
        return Datatables::of($deskripsi)->make(true);
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
        //untuk sub
        $k = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp')
            ->has('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanA = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'A');
            })
            ->doesntHave('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanB = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'B');
            })
            ->doesntHave('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanC = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'C')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.1')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.2')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.3');
            })
            ->doesntHave('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanD = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'D');
            })
            ->doesntHave('mata_pelajaran.urutan_rapor_sisipan.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $sub = SubRaporSisipan::with('sub_rapor_sisipan_mp', 'jenis_mata_pelajaran')->get();


        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();

        // $rapor_sisipan = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->get();

        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            })->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();

        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;
        if ($setting == '0') {
            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'raporSisipanD', 'list_nilai', 'wali_kelas'));
        } elseif ($setting == '1') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
                            $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
                            $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
                            $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
                            $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
                            }
                        }
                    }
                }
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor2', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } elseif ($setting == '2') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
                            $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
                            $nilai_tugas = $list_komponen->firstWhere('urutan', 1);
                            $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
                            $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
                            $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
                            }
                        }
                    }
                }
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor3', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } else { }
    }
}
