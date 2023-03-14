<?php

namespace App\Http\Controllers\Kesiswaan\Ekstrakurikuler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\Models\PresensiEkskul;
use App\Models\Ekskul as Ekskul;
use App\Models\PelatihEkskulSet as PelatihEkskulSet;
use App\Models\PesertaEkskulSet as PesertaEkskulSet;
use App\Models\PengambilanEkskul as PengambilanEkskul;
use App\Models\NilaiEkskul as NilaiEkskul;
use App\Models\Kelas as Kelas;
use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\EkskulWajib as EkskulWajib;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Semester as Semester;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class SettingPesertaEkskulController extends BaseController
{
    public function viewSettingPesertaEkskul(Request $request, $id_semester = null, $id_ekskul = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $ekskul = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('kesiswaan/ekstrakurikuler/setting-peserta-ekskul/view-setting-peserta-ekskul', compact('auth_data', 'ekskul', 'id_ekskul', 'selected_semester', 'data_semester','id_semester'));
    }

    public function actionViewSettingPesertaEkskul(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_ekskul' => 'required',
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
                'path' => 'ekstrakurikuler/setting-peserta-ekskul/view-ekskul/' . $input->id_semester .'/' . $input->id_ekskul
            ];
        }
    }

    public function viewEkskulSettingPesertaEkskul(Request $request,$id_semester, $id_ekskul)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $ekskul = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data); 
        $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        $ekskul_pilih = Ekskul::where('id_ekskul', '=', $id_ekskul)->first();
        return view('kesiswaan/ekstrakurikuler/setting-peserta-ekskul/view-setting-peserta-ekskul', compact('auth_data', 'ekskul', 'id_ekskul', 'ekskul_pilih','data_semester','selected_semester','id_semester'));
    }

    public function addSettingPesertaEkskul(Request $request, $id_ekskul, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $ekskul = Ekskul::where('id_ekskul', '=', $id_ekskul)->first();

        return view('kesiswaan/ekstrakurikuler/setting-peserta-ekskul/view-kelas-setting-peserta-ekskul', compact('auth_data', 'id_ekskul', 'data_kelas', 'id_kelas', 'ekskul'));
    }

    public function actionAddSettingPesertaEkskul(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'ekstrakurikuler/setting-peserta-ekskul/view-kelas/' . $input->id_ekskul . '/' . $input->id_kelas
            ];
        }
    }

    public function viewKelasSettingPesertaEkskul(Request $request, $id_ekskul, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $ekskul = Ekskul::where('id_ekskul', '=', $id_ekskul)->first();
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $kelas_siswa = Siswa::where('id_kelas', '=', $id_kelas)->get();

        return view('kesiswaan/ekstrakurikuler/setting-peserta-ekskul/view-kelas-setting-peserta-ekskul', compact('auth_data', 'id_ekskul', 'data_kelas', 'id_kelas', 'data_kelas', 'kelas_siswa', 'ekskul'));
    }

    public function editSettingPesertaEkskul(Request $request, $id_peserta_ekskul_set)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_peserta = PesertaEkskulSet::join('pengambilan_ekskul', 'pengambilan_ekskul.id_ekskul', '=', 'peserta_ekskul_set.id_ekskul')
            ->join('ekskul', 'ekskul.id_ekskul', '=', 'pengambilan_ekskul.id_ekskul')
            ->join('siswa', 'siswa.id_siswa', '=', 'peserta_ekskul_set.id_siswa')
            ->join('pengguna', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
            ->where('peserta_ekskul_set.id_peserta_ekskul_set', '=', $id_peserta_ekskul_set)->first();

        return view('kesiswaan/ekstrakurikuler/setting-peserta-ekskul/edit-setting-peserta-ekskul', compact('auth_data', 'data_peserta'));
    }

    public function setSettingPesertaEkskul(Request $request, $id_ekskul)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $ekskul = Ekskul::where('id_ekskul', '=', $id_ekskul)->first();

        $semester   = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $now = (int) $semester->thn_akademik_semester + 1;

        $tahun_sebelum = (int) $semester->thn_akademik_semester - 2;

        $data_semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->whereBetween('thn_akademik_semester', [$tahun_sebelum, $now])
            ->orderBy('thn_akademik_semester', 'asc')
            ->orderBy('nm_semester', 'asc')
            ->get();

        $jumlah_pengambilan = PengambilanEkskul::where('id_ekskul', '=', $ekskul->id_ekskul)
            ->where('id_semester', '=', $semester->id_semester)
            ->count();

        return view('kesiswaan/ekstrakurikuler/setting-peserta-ekskul/set-setting-peserta-ekskul', compact('auth_data', 'id_ekskul', 'ekskul', 'semester', 'data_semester', 'jumlah_pengambilan'));
    }

    public function datatablesSettingPesertaEkskul(Request $request, $id_ekskul,$id_semester)
    {
        
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PesertaEkskulSet::select('peserta_ekskul_set.is_aktif', 'ekskul.id_ekskul', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'peserta_ekskul_set.id_peserta_ekskul_set')
            ->join('ekskul', 'ekskul.id_ekskul', '=', 'peserta_ekskul_set.id_ekskul')
            ->join('siswa', 'siswa.id_siswa', '=', 'peserta_ekskul_set.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->join('pengambilan_ekskul','pengambilan_ekskul.id_siswa', '=','peserta_ekskul_set.id_siswa')
            // ->where('pengambilan_ekskul.id_siswa','=', 'peserta_ekskul_set.id_siswa')
            ->where('pengambilan_ekskul.id_ekskul','=', $id_ekskul)
            ->where('peserta_ekskul_set.id_ekskul', '=', $id_ekskul)
            ->where('pengambilan_ekskul.id_semester','=', $id_semester)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('nm_siswa', function ($item) {
                return $item->nis_siswa . '-' . $item->nm_pengguna;
            })
            ->addColumn('is_aktif', function ($item) {
                if ($item->is_aktif == "0") {
                    return "Tidak Aktif";
                } else {
                    return "Aktif";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_peserta_ekskul_set
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesSiswaSettingPesertaEkskul(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Siswa::join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->where('siswa.id_kelas', '=', $id_kelas);

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionSettingPesertaEkskul(Request $request, $mode, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), []);
        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            // dd($input);
            // ACTION ADD
            if ($mode == 'add') {
                $semester = Semester::where('is_aktif_semester', '=', '1')->where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->first();
                DB::beginTransaction();

                try {
                    foreach ($input->id_siswa as $id_siswa) {
                        $cekSiswa = PesertaEkskulSet::where('peserta_ekskul_set.id_siswa', '=', $id_siswa)->where('peserta_ekskul_set.id_ekskul', '=', $id_ekskul)->first();
                        if ($cekSiswa) {
                            DB::rollback();
                            return [
                                'status' => 203, // GAGAL
                                'message' => 'Tambah Ekskul Gagal Dilakukan'
                            ];
                        }
                        $id_pengambilan_ekskul = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $id_peserta_ekskul_set = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                        $siswa = Siswa::find($id_siswa);

                        $pengambilan_ekskul                     = new PengambilanEkskul;
                        $pengambilan_ekskul->id_pengambilan_ekskul  = $id_pengambilan_ekskul;
                        $pengambilan_ekskul->id_ekskul          = $id_ekskul;
                        $pengambilan_ekskul->id_siswa           = $id_siswa;
                        $pengambilan_ekskul->id_kelas           = $siswa->id_kelas;
                        $pengambilan_ekskul->id_semester        = $semester->id_semester;
                        $pengambilan_ekskul->is_tampil          = 0;
                        $pengambilan_ekskul->created_at         = $now;
                        $pengambilan_ekskul->created_by         = $input->auth_data->pengguna->id_pengguna;

                        $pengambilan_ekskul->save();

                        $peserta_ekskul_set                         = new PesertaEkskulSet;
                        $peserta_ekskul_set->id_peserta_ekskul_set  = $id_peserta_ekskul_set;
                        $peserta_ekskul_set->id_siswa               = $id_siswa;
                        $peserta_ekskul_set->id_ekskul              = $id_ekskul;
                        $peserta_ekskul_set->is_aktif               = 1;
                        $peserta_ekskul_set->created_at             = $now;
                        $peserta_ekskul_set->created_by             = $input->auth_data->pengguna->id_pengguna;
                        $peserta_ekskul_set->save();
                    }
                    DB::commit();
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Input Peserta Ekskul Berhasil Dilakukan',
                        'path' => 'ekstrakurikuler/setting-peserta-ekskul/view-ekskul/' . $id_ekskul
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Input Peserta Ekskul Gagal Dilakukan'
                    ];
                }
            } elseif ($mode == "delete") {
                $peserta_ekskul_set = PesertaEkskulSet::where('peserta_ekskul_set.id_peserta_ekskul_set', '=', $id_ekskul)->first();
                $pengambilan_ekskul = PengambilanEkskul::where('pengambilan_ekskul.id_ekskul', '=', $peserta_ekskul_set->id_ekskul)->where('pengambilan_ekskul.id_siswa', '=', $peserta_ekskul_set->id_siswa)->first();

                if ($pelatih = NilaiEkskul::where('id_pengambilan_ekskul', '=', $pengambilan_ekskul->id_pengambilan_ekskul)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Tidak bisa dihapus karna siswa sudah diberi nilai ekskul'
                    ];
                } else {
                    // make object to find id
                    $ekskul                 = PesertaEkskulSet::find($id_ekskul);
                    $ekskul->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                    $ekskul->save();

                    $ekskul->delete();

                    $pengambilan = PengambilanEkskul::find($pengambilan_ekskul->id_pengambilan_ekskul);
                    $pengambilan->deleted_by = $input->auth_data->pengguna->id_pengguna;
                    $pengambilan->save();

                    $pengambilan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Peserta Ekskul Successfully'
                    ];
                }
            } elseif ($mode == "edit") {
                $peserta_ekskul_set = PesertaEkskulSet::where('peserta_ekskul_set.id_peserta_ekskul_set', '=', $id_ekskul)->first();
                $pengambilan_ekskul = PengambilanEkskul::where('pengambilan_ekskul.id_ekskul', '=', $peserta_ekskul_set->id_ekskul)->where('pengambilan_ekskul.id_siswa', '=', $peserta_ekskul_set->id_siswa)->first();

                $peserta_ekskul = PesertaEkskulSet::find($id_ekskul);
                $peserta_ekskul->is_aktif = $input->is_aktif;
                $peserta_ekskul->updated_by = $input->auth_data->pengguna->id_pengguna;
                $peserta_ekskul->updated_at = $now;

                $peserta_ekskul->save();

                $pengambilan = PengambilanEkskul::find($pengambilan_ekskul->id_pengambilan_ekskul);
                $pengambilan->is_tampil = $input->is_tampil;
                $pengambilan->updated_by = $input->auth_data->pengguna->id_pengguna;
                $pengambilan->updated_at = $now;

                $pengambilan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'message' => 'Edit Data Peserta Ekskul Berhasil Dilakukan',
                    'path' => 'ekstrakurikuler/setting-peserta-ekskul/view-ekskul/' . $id_ekskul
                ];
            } elseif ($mode == "setting") {
                $id_ekskul = $input->id_ekskul;
                $id_semester = $input->id_semester;

                DB::beginTransaction();

                try {

                    // proses tabel pengambilan_ekskul
                    $peserta_ekskul_set = PesertaEkskulSet::where('id_ekskul', '=', $id_ekskul)->where('is_aktif', '=', 1)->get();
                    $batch_insert_pengambilan_ekskul = [];

                    foreach ($peserta_ekskul_set as $peserta_ekskul) {
                        $id_pengambilan_ekskul      = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $id_siswa                   = $peserta_ekskul->id_siswa;

                        $siswa                      = Siswa::where('id_siswa', '=', $id_siswa)->first();
                        $id_kelas                   = $siswa->id_kelas;

                        $cek_siswa = PengambilanEkskul::where('id_siswa', '=', $id_siswa)->where('id_ekskul', '=', $id_ekskul)->exists();
                        if ($cek_siswa) {
                            continue;
                        }

                        $batch_insert_pengambilan_ekskul[] = array(
                            'id_pengambilan_ekskul'     => $id_pengambilan_ekskul,
                            'id_ekskul'                 => $id_ekskul,
                            'id_siswa'                  => $id_siswa,
                            'id_kelas'                  => $id_kelas,
                            'id_semester'               => $id_semester,
                            'is_tampil'                 => 0,
                            'created_by'                => $input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now
                        );
                    }

                    \App\Jobs\CopySettingPengambilanEkskul::dispatch($batch_insert_pengambilan_ekskul);

                    DB::commit();
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Setting Pengambilan Ekskul Berhasil',
                        'path' => 'ekstrakurikuler/setting-peserta-ekskul/setting/' . $id_ekskul
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine()
                    ];
                }
            }
        }
    }
}
