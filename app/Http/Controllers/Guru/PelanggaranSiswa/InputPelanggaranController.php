<?php

namespace App\Http\Controllers\Guru\PelanggaranSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PresensiMp as PresensiMp;
use App\Models\PresensiMpPelanggaran as PresensiMpPelanggaran;
use App\Models\Siswa as Siswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class InputPelanggaranController extends BaseController
{
    public function viewInputPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        $grup_kbm_perhari = $data_kbm->groupBy('nm_jadwal_hari');

        return view('guru/pelanggaran-siswa/input-pelanggaran/view-input-pelanggaran-mp', compact('auth_data', 'grup_kbm_perhari'));
    }

    public function viewRekapInputPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/pelanggaran-siswa/input-pelanggaran/rekap-input-pelanggaran-mp', compact('auth_data'));
    }

    public function ajaxGetPertemuanByJadwalKelasMp(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $id_jadwal_kelas_mp = $input->id_jadwal_kelas_mp;

        $data_pertemuan = array();
        $data_presensiMp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->get();

        for ($i=1; $i < ($data_presensiMp->count() +1); $i++) {
            $presensiMp = $data_presensiMp->firstWhere('pertemuan_ke', $i);
            if ($presensiMp) {
                $pertemuan = array(
                    'text' => $i." (Sudah Absensi)",
                    'value' => $i
                );
            } else {
                $pertemuan = array(
                    'text' => $i,
                    'value' => $i
                );
            }

            $data_pertemuan[] = $pertemuan;
        }

        return $data_pertemuan;
    }

    // ==== ACTION PRESENSI KBM ====
    public function actionViewKBMInputPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'pelanggaran-siswa/input-pelanggaran-mp/view-kbm/'.$input->id_jadwal_kelas_mp.'/'.$input->pertemuan_ke
            ];
        }
    }

    public function viewKBMInputPelanggaran(Request $request, $id_jadwal_kelas_mp, $pertemuan_ke)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $id_jadwal_kelas_mp);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();
        
        return view('guru/pelanggaran-siswa/input-pelanggaran/view-kbm-input-pelanggaran-mp', compact('auth_data', 'semester_aktif', 'data_kelas', 'presensi_mp_aktif'));
    }
    
    public function datatablesInputPelanggaran(Request $request, $id_presensi_mp)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $presensi_mp_aktif = PresensiMp::where('id_presensi_mp', '=', $id_presensi_mp)->first();

        $list_data = LibSiswa::fetchDataSiswaKelasMp($auth_data, $presensi_mp_aktif->id_kelas_mp, $presensi_mp_aktif->pertemuan_ke);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesRekapInputPelanggaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataPelanggaran::fetchDataPresensiPelanggaran($auth_data);

        return Datatables::of($list_data)
                ->addColumn('nm_siswa', function ($item) {
                    return $item->nm_pengguna;
                })
                ->addColumn('is_sudah_tindakan', function ($item) {
                    if ($item->is_sudah_tindakan == 0) {
                        return "Belum";
                    } elseif ($item->is_sudah_tindakan == 1) {
                        return "Sudah";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_presensi_mp_pelanggaran,
                        'is_sudah_tindakan' => $item->is_sudah_tindakan
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addInputPelanggaran(Request $request, $id_presensi_mp, $id_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        
        $presensi_mp_aktif = PresensiMp::where('id_presensi_mp', '=', $id_presensi_mp)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $presensi_mp_aktif->id_jadwal_kelas_mp);
        
        // ambil data siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $id_siswa);

        $id_presensi_mp_pelanggaran = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        $data_kategori = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        return view('guru/pelanggaran-siswa/input-pelanggaran/add-input-pelanggaran-mp', compact('auth_data', 'data_kelas', 'data_siswa', 'presensi_mp_aktif', 'id_presensi_mp_pelanggaran', 'data_kategori'));
    }

    public function ajaxGetSubkategoriByKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data subkategori by kategori
        $data_subkategori = LibDataPelanggaran::fetchDataSubkategoriPelanggaranByKategori($auth_data, $input->kategori);

        return $data_subkategori;
    }

    public function editInputPelanggaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataPresensiPelanggaran($auth_data, $id);

        $presensi_mp_aktif = PresensiMp::where('id_presensi_mp', '=', $data_pelanggaran_siswa->id_presensi_mp)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $presensi_mp_aktif->id_jadwal_kelas_mp);
        
        // ambil data siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $data_pelanggaran_siswa->id_siswa);

        $data_kategori = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        $data_subkategori = LibDataPelanggaran::fetchDataSubkategoriPelanggaranByKategori($auth_data, $data_pelanggaran_siswa->id_kategori_pelanggaran);

        return view('guru/pelanggaran-siswa/input-pelanggaran/edit-input-pelanggaran-mp', compact('auth_data', 'data_kelas', 'data_siswa', 'presensi_mp_aktif', 'data_pelanggaran_siswa', 'data_kategori', 'data_subkategori'));
    }

    // Action POST
    public function actionInputPelanggaran(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_presensi_mp'              => 'required',
            'id_siswa'              => 'required',
            'id_subkategori_pelanggaran'    => 'required',
            'catatan_pelanggaran'   => 'required',
        ]);
        
        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first();

                $presensiMpPelanggaran                               = new PresensiMpPelanggaran;
                $presensiMpPelanggaran->id_presensi_mp_pelanggaran   = $id;
                $presensiMpPelanggaran->id_presensi_mp               = $input->id_presensi_mp;
                $presensiMpPelanggaran->id_siswa                     = $input->id_siswa;
                $presensiMpPelanggaran->id_kelas                     = $input->id_kelas;
                $presensiMpPelanggaran->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                $presensiMpPelanggaran->catatan_pelanggaran          = $input->catatan_pelanggaran;
                // convert format date
                $presensiMpPelanggaran->is_sudah_tindakan            = 0;
                $presensiMpPelanggaran->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $presensiMpPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pelanggaran-siswa/rekap-input-pelanggaran-mp',
                    'message' => 'Save Pelanggaran Siswa successfully'
                ];
            } elseif ($mode == 'edit') {
                $siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first();

                // make object to find id
                $presensiMpPelanggaran                               = PresensiMpPelanggaran::find($id);
                $presensiMpPelanggaran->id_siswa                     = $input->id_siswa;
                $presensiMpPelanggaran->id_kelas                     = $input->id_kelas;
                $presensiMpPelanggaran->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                $presensiMpPelanggaran->catatan_pelanggaran          = $input->catatan_pelanggaran;
                // convert format date
                $presensiMpPelanggaran->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $presensiMpPelanggaran->updated_at                   = $now;
                $presensiMpPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pelanggaran-siswa/rekap-input-pelanggaran-mp',
                    'message' => 'Update Pelanggaran Siswa successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($tindakanPelanggaran = TindakanPelanggaran::where('id_presensi_mp_pelanggaran', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Pelanggaran Siswa'
                    ];
                } else {
                    // make object to find id
                    $presensiMpPelanggaran               = PresensiMpPelanggaran::find($id);
                    $presensiMpPelanggaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $presensiMpPelanggaran->save();

                    $presensiMpPelanggaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Pelanggaran Siswa successfully'
                    ];
                }
            }
        }
    }
}
