<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum;
use App\Models\MataPelajaran;
use App\Models\Semester;
use App\Models\KelasMp;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PengampuMp;
use App\Models\JadwalHari;
use App\Models\Ruangan;
use App\Models\JadwalJam;
use App\Models\JadwalKelasMp;
use App\Models\PengambilanMp;
use App\Models\PresensiMpSiswa;
use App\Models\NilaiMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class HapusPlottingMapelSiswaController extends BaseController
{
    //
    public function viewHapusPlottingMapelSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/aktivitas-semester/hapus-plotting-mapel-siswa/view-hapus-plotting-mapel-siswa', compact('auth_data', 'data_semester'));
    }

    public function actionViewHapusPlottingMapelSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'aktivitas-semester/hapus-plotting-mapel-siswa/view-semester-hapus-plotting-mapel-siswa/' . $input->id_semester
            ];
        }
    }

    public function viewSemesterHapusPlottingMapelSiswa(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = Semester::where('id_semester', '=', $id)->first();

        return view('akademik/aktivitas-semester/hapus-plotting-mapel-siswa/view-semester-hapus-plotting-mapel-siswa', compact('auth_data', 'semester', 'id'));
    }

    public function viewDetailHapusPlottingMapelSiswa(Request $request, $id_kelas_mp)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_jadwal = JadwalKelasMp::with('ruangan', 'jadwal_hari', 'jadwal_jam_mulai', 'jadwal_jam_selesai', 'kelas_mp')
            ->whereHas('kelas_mp', function ($q) use ($semester_aktif) {
                $q->where('id_semester', $semester_aktif->id_semester);
            })->where('id_kelas_mp', $id_kelas_mp)->get();

        $data_kelas = KelasMp::with('kelas', 'mata_pelajaran')->where('id_kelas_mp', $id_kelas_mp)->first();

        $data_siswa = PengambilanMp::with('siswa', 'siswa.pengguna')->where('id_kelas_mp', $id_kelas_mp)->where('id_semester', $semester_aktif->id_semester)->get();

        return view('akademik/aktivitas-semester/hapus-plotting-mapel-siswa/detail-hapus-plotting-mapel-siswa', compact('auth_data', 'semester_aktif', 'data_jadwal', 'data_kelas', 'data_siswa'));
    }

    public function datatablesHapusPlottingMapelSiswa(Request $request, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = KelasMp::with(
            'mata_pelajaran',
            'mata_pelajaran.jenis_mata_pelajaran',
            'kelas',
            'jadwal_kelas_mp.jadwal_hari',
            'jadwal_kelas_mp.ruangan',
            'jadwal_kelas_mp.jadwal_jam_mulai',
            'jadwal_kelas_mp.jadwal_jam_selesai',
            'pengampu_mp.guru.pengguna'
        )
            // ->with(['pengambilan_mp' => function ($q) {
            //     $q->where('status_apv_pengambilan_mp', 1);
            // }])
            ->where('id_semester', '=', $id_semester);

        return Datatables::of($list_data)
            ->addColumn('jml_siswa', function ($item) {
                return $item->pengambilan_mp->count();
            })
            ->addColumn('jml_jadwal', function ($item) {
                $jadwal_kelas =  $item->jadwal_kelas_mp->first();

                $jadwal_kelas ?
                    $jadwal =  $jadwal_kelas->jadwal_hari->nm_jadwal_hari . ', ' .  $jadwal_kelas->jadwal_jam_mulai->jam_mulai . ':' . $jadwal_kelas->jadwal_jam_mulai->menit_mulai . '-' . $jadwal_kelas->jadwal_jam_selesai->jam_mulai . ':' . $jadwal_kelas->jadwal_jam_selesai->menit_mulai . ' ' . $jadwal_kelas->ruangan->nm_ruangan : $jadwal = null;

                return $jadwal;
            })->addColumn('pengampu', function ($item) {
                $pengguna = $item->pengampu_mp->first();
                $pengguna ?
                    $nm_pengguna =   $pengguna->guru->pengguna->gelar_depan . ' ' . $pengguna->guru->pengguna->nm_pengguna . ' ' . $pengguna->guru->pengguna->gelar_belakang : $nm_pengguna = null;
                return $nm_pengguna;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kelas_mp
                );
                return $data;
            })
            ->make(true);
    }

    public function actionHapusPlottingMapelSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'id_kelas_mp' => 'required',
            'id_siswa' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            DB::beginTransaction();

            try {
                if ($pengambilan_mp = PengambilanMp::where('id_kelas_mp', $input->id_kelas_mp)->where('id_semester', $input->id_semester)->where('id_siswa', $input->id_siswa)->first()) {
                    $presensi_mp_siswa = PresensiMpSiswa::with('presensi_mp')->whereHas('presensi_mp', function ($q) use ($input) {
                        $q->where('id_kelas_mp', $input->id_kelas_mp);
                    })->where('id_siswa', $input->id_siswa)->get();

                    foreach ($presensi_mp_siswa as $item) {
                        $item->deleted_by = $auth_data->pengguna->id_pengguna;
                        $item->save();
                        $item->delete();
                    }

                    $nilai_mp = NilaiMp::where('id_pengambilan_mp', $pengambilan_mp->id_pengambilan_mp)->get();

                    foreach ($nilai_mp as $item) {
                        $item->deleted_by = $auth_data->pengguna->id_pengguna;
                        $item->save();
                        $item->delete();
                    }

                    $pengambilan_mp->deleted_by = $auth_data->pengguna->id_pengguna;
                    $pengambilan_mp->save();
                    $pengambilan_mp->delete();
                }

                DB::commit();
                // all good

                return [
                    'status'    =>  202, // SUCCESS AND LOAD CONTENT
                    'message'   =>  'Delete Plotting Mapel Siswa Successfully',
                    'path'      =>  'aktivitas-semester/hapus-plotting-mapel-siswa/view-detail-hapus-plotting-mapel-siswa/' . $input->id_kelas_mp
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong

                return [
                    'status' => 300, // GAGAL
                    'message' => 'Edit Usulan Mata Ajar Gagal! '
                ];
            }
        }
    }

    function actionHapusSemuaPlottingMapelSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'id_kelas_mp' => 'required',
            'id_siswa' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            DB::beginTransaction();

            try {
                foreach ($input->id_siswa as $id_siswa) {
                    if ($pengambilan_mp = PengambilanMp::where('id_kelas_mp', $input->id_kelas_mp)->where('id_semester', $input->id_semester)->where('id_siswa', $id_siswa)->first()) {
                        $presensi_mp_siswa = PresensiMpSiswa::with('presensi_mp')->whereHas('presensi_mp', function ($q) use ($input) {
                            $q->where('id_kelas_mp', $input->id_kelas_mp);
                        })->where('id_siswa', $id_siswa)->get();

                        foreach ($presensi_mp_siswa as $item) {
                            $item->deleted_by = $auth_data->pengguna->id_pengguna;
                            $item->save();
                            $item->delete();
                        }

                        $nilai_mp = NilaiMp::where('id_pengambilan_mp', $pengambilan_mp->id_pengambilan_mp)->get();

                        foreach ($nilai_mp as $item) {
                            $item->deleted_by = $auth_data->pengguna->id_pengguna;
                            $item->save();
                            $item->delete();
                        }

                        $pengambilan_mp->deleted_by = $auth_data->pengguna->id_pengguna;
                        $pengambilan_mp->save();
                        $pengambilan_mp->delete();
                    }
                }

                DB::commit();
                // all good

                return [
                    'status'    =>  202, // SUCCESS AND LOAD CONTENT
                    'message'   =>  'Delete Plotting Mapel Siswa Successfully',
                    'path'      =>  'aktivitas-semester/hapus-plotting-mapel-siswa/view-detail-hapus-plotting-mapel-siswa/' . $input->id_kelas_mp
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong

                return [
                    'status' => 300, // GAGAL
                    'message' => 'Edit Usulan Mata Ajar Gagal! '
                ];
            }
        }
    }

    function viewHapusPlotingOtomatis(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_siswa = PengambilanMp::select('id_siswa', 'id_kelas_mp', DB::raw('COUNT(id_siswa) as count'))
            ->with('siswa', 'siswa.pengguna', 'kelas_mp.kelas', 'kelas_mp.mata_pelajaran.jenis_mata_pelajaran')
            ->groupBy('id_siswa', 'id_kelas_mp')
            ->havingRaw('COUNT(id_siswa) > 1')
            ->orderBy('id_siswa')
            ->where('id_semester', $semester_aktif->id_semester)
            // ->take(10)
            ->get();


        return view('akademik/aktivitas-semester/hapus-plotting-mapel-siswa/view-hapus-plotting-mapel-siswa-otomatis', compact('auth_data', 'semester_aktif', 'data_siswa'));
    }
    function actionHapusPlotingOtomatis(Request $request)
    {
        set_time_limit(-1);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_siswa = PengambilanMp::select('id_siswa', 'id_kelas_mp', DB::raw('COUNT(id_siswa) as count'))
            ->with('siswa', 'siswa.pengguna', 'kelas_mp.kelas', 'kelas_mp.mata_pelajaran.jenis_mata_pelajaran')
            ->groupBy('id_siswa', 'id_kelas_mp')
            ->havingRaw('COUNT(id_siswa) > 1')
            ->whereDoesntHave('nilai_mp')
            ->orderBy('id_siswa')
            ->where('id_semester', $semester_aktif->id_semester)
            ->take(500)
            ->get();


        // dd($data_siswa);
        // $data_siswa->each->delete();
        foreach ($data_siswa as $siswa) {
            PengambilanMp::where('id_siswa', $siswa->id_siswa)->where('id_kelas_mp', $siswa->id_kelas_mp)->delete();
            // $siswa->delete();
        }
        // dd($data_siswa);
        return [
            'status'    =>  202, // SUCCESS AND LOAD CONTENT
            'message'   =>  'Delete Plotting Mapel Siswa Otomatis Successfully',
            'path'      =>  'aktivitas-semester/hapus-plotting-mapel-siswa-otomatis'
        ];
    }
}
