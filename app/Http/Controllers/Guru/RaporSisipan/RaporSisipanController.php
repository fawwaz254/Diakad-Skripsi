<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use App\Exports\RaporSisipanSTS;
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

        return view('guru/rapor-sisipan/daftar-nilai-sts/view-daftar-nilai-sts', compact('auth_data'));
    }

    public function addDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data['list_mapel'] = MataPelajaran::all();
        // $data['list_jurusan'] = Jurusan::all();
        $data['list_kelas'] = Kelas::all();

        return view('guru/rapor-sisipan/daftar-nilai-sts/add-daftar-nilai-sts', compact('auth_data'), $data);
    }

    public function actionDaftarNilaiSTS(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        // dd($semester_aktif);
        if ($mode == 'delete') {

            $raporSisipan = RaporSisipan::where('id_rapor_sisipan', $id)->first();
            $raporSisipan->deleted_by =  $input->auth_data->pengguna->id_pengguna;
            $raporSisipan->save();
            $raporSisipan->delete();

            $nilaiRaporSisipan = NilaiRaporSisipan::where('id_rapor_sisipan', $id)->get();
            foreach ($nilaiRaporSisipan as $id) {
                $nilai = NilaiRaporSisipan::where('id_nilai_rapor_sisipan', $id->id_nilai_rapor_sisipan)->first();
                $nilai->deleted_by = $input->auth_data->pengguna->id_pengguna;
                $nilai->save();
                $nilai->delete();
            }

            return [
                'status' => 202,
                'path' => 'rapor-sisipan/daftar-nilai-sts',
                'message' => 'Delete Rapor Sisipan successfully'
            ];
        }

        if ($mode == 'add') {
            $cekDuplicate = RaporSisipan::where('id_kelas',$input->id_kelas)->where('id_mata_pelajaran',$input->id_mata_pelajaran)->where('id_semester',$semester_aktif->id_semester)->first();
            $validator = Validator::make($request->all(), [
                'id_mata_pelajaran' => 'required',
                'id_kelas'              => 'required'
            ]);
        }

        if($cekDuplicate){
            return [
                'status' => 300, // FAILED
                'message' => 'Kelas dan Mapel Sudah ada Guru Lain yang Menggunakan'
            ];
        }

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $rapor_sisipan                      = new RaporSisipan;
                    $rapor_sisipan->id_rapor_sisipan    = $id;
                    $rapor_sisipan->id_semester         = $semester_aktif->id_semester;
                    $rapor_sisipan->id_mata_pelajaran   = $input->id_mata_pelajaran;
                    $rapor_sisipan->id_kelas            = $input->id_kelas;
                    $rapor_sisipan->id_pengguna         = $input->auth_data->pengguna->id_pengguna;
                    $rapor_sisipan->created_by          = $input->auth_data->pengguna->id_pengguna;
                    $rapor_sisipan->save();

                    $siswa = Siswa::where('id_kelas', $input->id_kelas)->get();
                    $komponen_nilai = KomponenNilaiRaporSisipan::all();
                    foreach ($siswa as $s) {
                        foreach ($komponen_nilai as $komponen) {
                            $id = $input->auth_data->sekolah_data->prefix . strtotime(Carbon::now(env('APP_TIMEZONE', ''))) . uniqid();
                            $nilai_rapor_sisipan                                    = new NilaiRaporSisipan;
                            $nilai_rapor_sisipan->id_nilai_rapor_sisipan            = $id;
                            $nilai_rapor_sisipan->id_rapor_sisipan                  = $rapor_sisipan->id_rapor_sisipan;
                            $nilai_rapor_sisipan->id_komponen_nilai                 = $komponen->id_komponen_nilai;
                            $nilai_rapor_sisipan->id_siswa                          = $s->id_siswa;
                            $nilai_rapor_sisipan->nilai                             = 0;
                            $nilai_rapor_sisipan->created_by                        = $input->auth_data->pengguna->id_pengguna;
                            $nilai_rapor_sisipan->save();
                        }
                    }
                    DB::Commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'rapor-sisipan/daftar-nilai-sts',
                        'message' => 'Save Tambah Nilai successfully'
                    ];
                } catch (\Exception $e) {

                    DB::rollback();

                    return [
                        'status' => 203, // GAGAL
                        'message' => $e->getMessage()
                    ];
                }
            }
        }
    }

    public function datatablesDaftarNilaiSTS(Request $request)
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
                $belumTerisi = NilaiRaporSisipan::where('nilai', 0)->with('siswa.kelas')->whereHas('siswa.kelas', function ($query) use ($item) {
                    $query->where('id_kelas', '=', $item->kelas->id_kelas);
                })->groupBy('id_siswa')
                    ->selectRaw('count(*) as total, id_siswa')
                    ->get()->toArray();

                //hitung ada berapa nilai kosongnya
                $arrayJumlahBelumTerisi = array_count_values(array_column($belumTerisi, 'total'));

                //loop dan cari nilai kosong yang diatas 5
                $nilaiSiswaYangKosong = 0;
                for ($i = 6; $i <= 10; $i++) {
                    if (isset($arrayJumlahBelumTerisi[$i])) {
                        $nilaiSiswaYangKosong += $arrayJumlahBelumTerisi[$i];
                    }
                }

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


    public function printDaftarNilaiSTS (Request $request, $id_rapor_sisipan){

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan',$id_rapor_sisipan)->with('mata_pelajaran','kelas')->first();
    
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

        $data['nilai_siswa'] = $nilai_siswa;
        $data['nilai_komponen'] = $nilai_komponen;
        $data['rapor_sisipan'] = $rapor_sisipan;
        $data['list_siswa']= $list_siswa;;
        $data['list_data']= $list_data;
        $data['id_rapor_sisipan'] = $id_rapor_sisipan;

        return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS.xlsx');
    }
}
