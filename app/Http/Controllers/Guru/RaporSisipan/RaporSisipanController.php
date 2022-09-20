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

            // $materi = MateriAjar::where('id_materi_ajar', $id)->first();
            // $materi->delete();

            // $materiFile = MateriAjarFile::where('id_materi_ajar', $id)->get();
            // foreach ($materiFile as $file) {
            //     Storage::delete($file['link_file']);
            //     $file->delete();
            // }

            // return [
            //     'status' => 202,
            //     'path' => 'e-learning/manajemen-materi-ajar',
            //     'message' => 'Delete Materi Ajar successfully'
            // ];
        }

        if ($mode == 'add') {
            $validator = Validator::make($request->all(), [
                'id_mata_pelajaran' => 'required',
                'id_kelas'              => 'required'
            ]);
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
                $data = array(
                    'jumlah_siswa' => Siswa::where('id_kelas', $item->kelas->id_kelas)->count(),
                    'terisi_siswa' => '0',
                );
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
}
