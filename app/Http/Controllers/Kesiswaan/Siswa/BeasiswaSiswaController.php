<?php

namespace App\Http\Controllers\Kesiswaan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Siswa as Siswa;
use App\Models\BeasiswaSiswa as BeasiswaSiswa;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class BeasiswaSiswaController extends BaseController
{
    public function viewBeasiswaSiswa(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('kesiswaan/siswa/beasiswa-siswa/view-beasiswa-siswa', compact('auth_data', 'id_kelas'));
    }

    public function addBeasiswaSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/beasiswa-siswa/add-beasiswa-siswa', compact('auth_data', 'data_kelas'));
    }

    public function editBeasiswaSiswa(Request $request, $id_beasiswa_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        
        $beasiswa = BeasiswaSiswa::select('beasiswa_siswa.jenis_beasiswa_siswa', 'beasiswa_siswa.keterangan_beasiswa_siswa', 'beasiswa_siswa.tahun_mulai_beasiswa_siswa', 'beasiswa_siswa.tahun_selesai_beasiswa_siswa', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'beasiswa_siswa.id_beasiswa_siswa', 'siswa.id_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'beasiswa_siswa.id_siswa')
        ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->where('beasiswa_siswa.id_beasiswa_siswa', '=', $id_beasiswa_siswa)
        ->first();
        // dd($beasiswa);
        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/beasiswa-siswa/edit-beasiswa-siswa', compact('auth_data', 'beasiswa'));
    }

    public function ajaxGetSiswaByKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $input->kelas);

        return $data_siswa;
    }

    public function datatablesBeasiswaSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = BeasiswaSiswa::select('beasiswa_siswa.jenis_beasiswa_siswa', 'beasiswa_siswa.keterangan_beasiswa_siswa', 'beasiswa_siswa.tahun_mulai_beasiswa_siswa', 'beasiswa_siswa.tahun_selesai_beasiswa_siswa', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'beasiswa_siswa.id_beasiswa_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'beasiswa_siswa.id_siswa')
        ->join('kelas', 'kelas.id_kelas', '=', 'beasiswa_siswa.id_kelas')
        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
                ->addColumn('jenis_beasiswa_siswa', function ($item) {
                    if ($item->jenis_beasiswa_siswa == 1) {
                        return "Anak Berprestasi";
                    } elseif ($item->jenis_beasiswa_siswa == 2) {
                        return "Anak Miskin";
                    } elseif ($item->jenis_beasiswa_siswa == 3) {
                        return "Pendidikan";
                    } elseif ($item->jenis_beasiswa_siswa == 99) {
                        return "Lain-Lain";
                    } elseif ($item->jenis_beasiswa_siswa == 4) {
                        return "Unggulan";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_beasiswa_siswa
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionBeasiswaSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'jenis_beasiswa_siswa' => 'required',
            'id_siswa' => 'required',
            'tahun_mulai_beasiswa_siswa' => 'required', 'tahun_selesai_beasiswa_siswa' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // ACTION ADD
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $siswa = Siswa::find($input->id_siswa);
                
                $beasiswa 								= new BeasiswaSiswa;
                $beasiswa->id_beasiswa_siswa 			= $id;
                $beasiswa->id_siswa 					= $input->id_siswa;
                $beasiswa->id_kelas                     = $siswa->id_kelas;
                $beasiswa->jenis_beasiswa_siswa 		= $input->jenis_beasiswa_siswa;
                $beasiswa->tahun_mulai_beasiswa_siswa 	= $input->tahun_mulai_beasiswa_siswa;
                $beasiswa->tahun_selesai_beasiswa_siswa = $input->tahun_selesai_beasiswa_siswa;
                $beasiswa->keterangan_beasiswa_siswa 	= $input->keterangan_beasiswa_siswa;
                $beasiswa->created_by 					= $input->auth_data->pengguna->id_pengguna;
                $beasiswa->created_at 					= $now;
                $beasiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/beasiswa-siswa',
                    'message' => 'Save Data Beasiswa Siswa successfully'
                ];
            } elseif ($mode == 'edit') {
                $beasiswa 								= BeasiswaSiswa::find($id);

                if($beasiswa->id_siswa != $input->siswa) {
                    $siswa = Siswa::find($input->id_siswa);
                    $beasiswa->id_siswa                     = $input->id_siswa;
                    $beasiswa->id_kelas                     = $siswa->id_kelas;
                }
                $beasiswa->jenis_beasiswa_siswa 		= $input->jenis_beasiswa_siswa;
                $beasiswa->tahun_mulai_beasiswa_siswa 	= $input->tahun_mulai_beasiswa_siswa;
                $beasiswa->keterangan_beasiswa_siswa 	= $input->keterangan_beasiswa_siswa;
                $beasiswa->tahun_selesai_beasiswa_siswa = $input->tahun_selesai_beasiswa_siswa;
                $beasiswa->created_by 					= $input->auth_data->pengguna->id_pengguna;
                $beasiswa->created_at 					= $now;
                $beasiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/beasiswa-siswa',
                    'message' => 'Save Data Beasiswa Siswa successfully'
                ];
            } elseif ($mode == 'delete') {
                $beasiswa                 = BeasiswaSiswa::find($id);
                $beasiswa->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                $beasiswa->save();

                $prestasi->delete();

                return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Beasiswa Siswa successfully'
                    ];
            }
        }
    }
}
