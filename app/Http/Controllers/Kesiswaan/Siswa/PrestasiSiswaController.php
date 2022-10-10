<?php

namespace App\Http\Controllers\Kesiswaan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Kelas as Kelas;
use App\Models\Semester as Semester;
use App\Models\Siswa as Siswa;
use App\Models\WaliMurid;
use App\Models\Guru as Guru;
use App\Models\Ekskul as Ekskul;
use App\Models\PrestasiSiswa as PrestasiSiswa;
use App\Models\TingkatPrestasiSiswa as TingkatPrestasiSiswa;

use App\Libraries\LibGlobal;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class PrestasiSiswaController extends BaseController
{
    public function viewPrestasiSiswa(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('kesiswaan/siswa/prestasi-siswa/view-prestasi-siswa', compact('auth_data', 'id_kelas'));
    }

    public function addPrestasiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_ekskul = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_semester = Semester::where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_tingkat_prestasi = TingkatPrestasiSiswa::where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/prestasi-siswa/add-prestasi-siswa', compact('auth_data', 'data_kelas', 'data_semester', 'data_tingkat_prestasi', 'data_ekskul', 'data_guru'));
    }

    public function editPrestasiSiswa(Request $request, $id_prestasi_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_ekskul = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_semester = Semester::where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_tingkat_prestasi = TingkatPrestasiSiswa::where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        $prestasi = PrestasiSiswa::join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->leftjoin('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->where('id_prestasi_siswa', '=', $id_prestasi_siswa)->first();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/prestasi-siswa/edit-prestasi-siswa', compact('auth_data', 'data_kelas', 'data_semester', 'data_tingkat_prestasi', 'data_ekskul', 'data_guru', 'prestasi'));
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

    public function datatablesPrestasiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PrestasiSiswa::select(
            'prestasi_siswa.nm_prestasi_siswa',
            'prestasi_siswa.link_sertif_prestasi_siswa',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            'prestasi_siswa.jenis_prestasi_siswa',
            'prestasi_siswa.peringkat_prestasi_siswa',
            'prestasi_siswa.jenis_lomba_siswa',
            'p1.nm_pengguna as nm_siswa',
            'siswa.nisn_siswa',
            'siswa.nis_siswa',
            'semester.nm_semester',
            'semester.tahun_ajaran',
            'kelas.nm_kelas',
            'prestasi_siswa.lokasi_prestasi_siswa',
            'prestasi_siswa.penyelenggara_prestasi_siswa',
            'prestasi_siswa.tgl_prestasi_siswa',
            'ekskul.nm_ekskul',
            'prestasi_siswa.id_prestasi_siswa',
            'prestasi_siswa.id_guru_pendamping',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
            ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_siswa.id_tingkat_prestasi_siswa')
            ->join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
            ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('semester', 'semester.id_semester', '=', 'prestasi_siswa.id_semester')
            ->join('kelas', 'kelas.id_kelas', '=', 'prestasi_siswa.id_kelas')
            ->leftJoin('ekskul', 'ekskul.id_ekskul', '=', 'prestasi_siswa.id_ekskul')
            ->leftJoin('guru', 'guru.id_guru', '=', 'prestasi_siswa.id_guru_pendamping')
            ->leftJoin('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
            ->orderBy('prestasi_siswa.created_at', 'desc')
            ->orderBy('semester.thn_akademik_semester', 'desc')
            ->orderBy('semester.nm_semester', 'desc')
            ->where('prestasi_siswa.status', 1)
            // ->where('p1.id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
            ->addIndexColumn()
            ->addColumn('semester', function ($item) {
                return $item->nm_semester . ' (' . $item->tahun_ajaran . ')';
            })
            ->addColumn('jenis_prestasi', function ($item) {
                if ($item->jenis_prestasi_siswa == 1) {
                    return "Sains";
                } elseif ($item->jenis_prestasi_siswa == 2) {
                    return "Seni";
                } elseif ($item->jenis_prestasi_siswa == 3) {
                    return "Olahraga";
                } elseif ($item->jenis_prestasi_siswa == 99) {
                    return "Lain-Lain";
                }
            })
            ->addColumn('tgl_prestasi_siswa', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_prestasi_siswa));
            })
            ->addColumn('nm_guru_pendamping', function ($item) {
                if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                    return $item->gelar_depan . " " . $item->nm_guru_pendamping . ", " . $item->gelar_belakang;
                } elseif (!empty($item->gelar_depan)) {
                    return $item->gelar_depan . " " . $item->nm_guru_pendamping;
                } elseif (!empty($item->gelar_belakang)) {
                    return $item->nm_guru_pendamping . ", " . $item->gelar_belakang;
                } else {
                    return $item->nm_guru_pendamping;
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_prestasi_siswa,
                    'link_sertif_prestasi_siswa' => $item->link_sertif_prestasi_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionPrestasiSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_prestasi_siswa' => 'required',
            'id_siswa' => 'required',
            'id_semester' => 'required',
            'jenis_lomba_siswa' => 'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'jenis_prestasi_siswa' => 'required',
            'link_sertifikat' => 'required',
            'lokasi_prestasi_siswa' => 'required', 'penyelenggara_prestasi_siswa' => 'required',
            'peringkat_prestasi_siswa' => 'required', 'tgl_prestasi_siswa' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // ACTION ADD

            if ($mode == 'add') {
                if ($siswa = Siswa::find($input->id_siswa)) {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $prestasi                                 = new PrestasiSiswa;
                    $prestasi->id_prestasi_siswa              = $id;
                    $prestasi->id_siswa                       = $input->id_siswa;
                    $prestasi->id_kelas                       = $siswa->id_kelas;
                    $prestasi->id_semester                    = $input->id_semester;
                    $prestasi->id_tingkat_prestasi_siswa      = $input->id_tingkat_prestasi_siswa;
                    $prestasi->id_guru_pendamping             = $input->id_guru_pendamping;
                    // $prestasi->id_ekskul                      = $input->id_ekskul;
                    $prestasi->jenis_prestasi_siswa           = $input->jenis_prestasi_siswa;
                    $prestasi->jenis_lomba_siswa              = $input->jenis_lomba_siswa;
                    $prestasi->nm_prestasi_siswa              = $input->nm_prestasi_siswa;
                    $prestasi->lokasi_prestasi_siswa          = $input->lokasi_prestasi_siswa;
                    $prestasi->penyelenggara_prestasi_siswa   = $input->penyelenggara_prestasi_siswa;
                    $prestasi->peringkat_prestasi_siswa       = $input->peringkat_prestasi_siswa;
                    $prestasi->tgl_prestasi_siswa             = date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
                    $prestasi->link_sertif_prestasi_siswa     = $input->link_sertifikat;
                    $prestasi->created_by                     = $input->auth_data->pengguna->id_pengguna;
                    $prestasi->created_at                     = $now;
                    $prestasi->status = 1;
                    $prestasi->approved_by = $auth_data->pengguna->id_pengguna;
                    $prestasi->approved_at = $now;
                    $prestasi->save();

                    $token_siswa = $siswa->pengguna->api_token;
                    if (!empty($token_siswa)) {
                        $message = 'Kamu telah tercatat mendapatkan prestasi';
                        $send_data = array(
                            'title' => 'Informasi',
                            'body' => $message,
                            'priority' => 'high',
                            'screen1' => '',
                            'screen2' => ''
                        );

                        $notifikasi = array(
                            'id' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_pengguna' => $siswa->pengguna->id_pengguna,
                            'id_sekolah' => $siswa->pengguna->id_sekolah,
                            'isi_notifikasi' => $message,
                            'created_by' => $input->auth_data->pengguna->id_pengguna
                        );

                        LibGlobal::sendNotification($token_siswa, $send_data, $notifikasi);
                    }

                    if (!empty($siswa->id_wali_murid)) {
                        $wali_murid = WaliMurid::find($siswa->id_wali_murid);
                        if ($wali_murid) {
                            $token_wali_murid = $wali_murid->pengguna->api_token;
                            if (!empty($token_wali_murid)) {
                                $message = 'Putra/Putri Anda telah tercatat mendapatkan prestasi';
                                $send_data = array(
                                    'title' => 'Informasi',
                                    'body' => $message,
                                    'priority' => 'high',
                                    'screen1' => '',
                                    'screen2' => ''
                                );

                                $notifikasi = array(
                                    'id' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                                    'id_pengguna' => $wali_murid->pengguna->id_pengguna,
                                    'id_sekolah' => $wali_murid->pengguna->id_sekolah,
                                    'isi_notifikasi' => $message,
                                    'created_by' => $input->auth_data->pengguna->id_pengguna
                                );

                                LibGlobal::sendNotification($token_wali_murid, $send_data, $notifikasi);
                            }
                        }
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-kesiswaan/prestasi-siswa',
                        'message' => 'Save Data Prestasi Siswa Successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Update Data Prestasi siswa gagal'
                    ];
                }
            } elseif ($mode == 'edit') {
                if ($siswa = Siswa::find($input->id_siswa)) {
                    $prestasi                                 = PrestasiSiswa::find($id);
                    if ($input->id_siswa != $prestasi->id_siswa) {
                        $prestasi->id_siswa                       = $input->id_siswa;
                        $prestasi->id_kelas                       = $siswa->id_kelas;
                    }
                    $prestasi->id_semester                    = $input->id_semester;
                    $prestasi->id_tingkat_prestasi_siswa      = $input->id_tingkat_prestasi_siswa;
                    $prestasi->id_guru_pendamping             = $input->id_guru_pendamping;
                    $prestasi->id_ekskul                      = $input->id_ekskul;
                    $prestasi->jenis_prestasi_siswa           = $input->jenis_prestasi_siswa;
                    $prestasi->jenis_lomba_siswa              = $input->jenis_lomba_siswa;
                    $prestasi->nm_prestasi_siswa              = $input->nm_prestasi_siswa;
                    $prestasi->lokasi_prestasi_siswa          = $input->lokasi_prestasi_siswa;
                    $prestasi->penyelenggara_prestasi_siswa   = $input->penyelenggara_prestasi_siswa;
                    $prestasi->peringkat_prestasi_siswa       = $input->peringkat_prestasi_siswa;
                    $prestasi->link_sertif_prestasi_siswa     = $input->link_sertifikat;
                    $prestasi->tgl_prestasi_siswa             = date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
                    $prestasi->updated_by                     = $input->auth_data->pengguna->id_pengguna;
                    $prestasi->updated_at                     = $now;
                    $prestasi->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-kesiswaan/prestasi-siswa',
                        'message' => 'Save Data Prestasi Siswa Successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Update Data Prestasi siswa gagal'
                    ];
                }
            } elseif ($mode == 'delete') {
                $prestasi                 = PrestasiSiswa::find($id);
                $prestasi->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                $prestasi->save();

                $prestasi->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Prestasi Siswa Successfully'
                ];
            }
        }
    }
}
