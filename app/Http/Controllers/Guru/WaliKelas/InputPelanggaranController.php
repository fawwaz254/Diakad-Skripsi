<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KategoriPelanggaran;
use App\Models\PelanggaranSiswa as PelanggaranSiswa;
use App\Models\TindakanPelanggaran as TindakanPelanggaran;
use App\Models\Guru as Guru;
use App\Models\Siswa as Siswa;
use App\Models\WaliMurid;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\LibGlobal;

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
        $auth_data = auth_data();

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        return view('guru/wali-kelas/input-pelanggaran/view-input-pelanggaran', compact('auth_data', 'wali_kelas'));
    }

    public function addInputPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas);

        // ambil data all kategori
        $data_kategori = KategoriPelanggaran::with('subkategori_pelanggaran')->where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();

        $id_pelanggaran_siswa = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('guru/wali-kelas/input-pelanggaran/add-input-pelanggaran', compact('auth_data', 'wali_kelas', 'data_semester', 'data_siswa', 'data_kategori', 'id_pelanggaran_siswa'));
    }

    public function editInputPelanggaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas);

        // ambil data all kategori
        $data_kategori = KategoriPelanggaran::with('subkategori_pelanggaran')->where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();

        $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, null, $id);

        // convert format date
        $tgl_pelanggaran = strftime("%d %B %Y %H:%M:%S", strtotime($data_pelanggaran_siswa->tgl_pelanggaran));

        return view('guru/wali-kelas/input-pelanggaran/edit-input-pelanggaran', compact('auth_data', 'wali_kelas', 'data_semester', 'data_siswa', 'data_kategori', 'data_pelanggaran_siswa', 'tgl_pelanggaran'));
    }

    public function ajaxGetSubkategoriByKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // ambil data subkategori by kategori
        $data_subkategori = LibDataPelanggaran::fetchDataSubkategoriPelanggaranByKategori($auth_data, $input->kategori);

        return $data_subkategori;
    }

    public function datatablesInputPelanggaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $list_data = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, $wali_kelas->id_kelas, null, "1");

        return Datatables::of($list_data)
            ->addColumn('nm_siswa', function ($item) {
                return $item->nm_pengguna;
            })
            ->addColumn('aktor_input_pelanggaran', function ($item) {
                switch ($item->aktor_input_pelanggaran) {
                    case 1:
                        return 'Role BK';
                        break;
                    case 2:
                        return 'Kesiswaan';
                        break;
                    case 3:
                        return 'Wali Kelas';
                        break;
                    case 4:
                        return 'Guru Reguler';
                        break;
                    case 5:
                        return 'Guru Piket';
                        break;
                    default:
                        return '';
                        break;
                }
            })
            ->addColumn('nm_input', function ($item) {
                if (!empty($item->nm_guru_input)) {
                    return $item->nm_guru_input . " (Guru)";
                } else {
                    return $item->nm_staff_input . " (Tendik)";
                }
            })
            ->addColumn('semester', function ($item) {
                return $item->tahun_ajaran . " " . $item->nm_semester;
            })
            ->addColumn('tingkat_pelanggaran', function ($item) {
                return $item->tingkat_kategori_pelanggaran . "." . $item->tingkat_subkategori_pelanggaran;
            })
            ->addColumn('tgl_pelanggaran', function ($item) {
                return strftime("%d %B %Y %H:%M:%S", strtotime($item->tgl_pelanggaran));
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
                    'id' => $item->id_pelanggaran_siswa,
                    'is_sudah_tindakan' => $item->is_sudah_tindakan
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionInputPelanggaran(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required',
            'id_subkategori_pelanggaran' => 'required',
            'id_semester' => 'required',
            'catatan_pelanggaran' => 'required',
            'tgl_pelanggaran' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {

                $time = Carbon::parse($input->tgl_pelanggaran)->toDateString();
                $pelanggaran = PelanggaranSiswa::whereDate('tgl_pelanggaran', $time)->where('id_semester', $input->id_semester)->where('id_siswa', $input->id_siswa)->where('id_subkategori_pelanggaran', $input->id_subkategori_pelanggaran)->first();

                $users = DB::table('siswa')
                    ->join('pengguna', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
                    ->where('id_siswa', $input->id_siswa)
                    ->first();

                if ($pelanggaran) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Data Pelanggaran ' . $users->nm_pengguna . ' sudah terinput'
                    ];
                }

                if (auth_data()->pengguna->status_join_table == 2) {
                    // get id_guru
                    $guru = Guru::select('id_guru')
                        ->where('id_pengguna', '=', auth_data()->pengguna->id_pengguna)
                        ->first();

                    $id_guru_input = $guru->id_guru;
                } else {
                    $id_guru_input = null;
                }

                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first();

                $pelanggaranSiswa = new PelanggaranSiswa;
                $pelanggaranSiswa->id_pelanggaran_siswa = $id;
                $pelanggaranSiswa->id_siswa = $input->id_siswa;
                $pelanggaranSiswa->id_kelas = $siswa->id_kelas;
                $pelanggaranSiswa->id_guru_input = $id_guru_input;
                $pelanggaranSiswa->id_semester = $input->id_semester;
                $pelanggaranSiswa->id_subkategori_pelanggaran = $input->id_subkategori_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran = $input->catatan_pelanggaran;
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran = date_format(date_create($input->tgl_pelanggaran), "Y-m-d H:i:s");
                $pelanggaranSiswa->aktor_input_pelanggaran = 3;
                $pelanggaranSiswa->is_sudah_tindakan = 0;
                $pelanggaranSiswa->created_by = auth_data()->pengguna->id_pengguna;
                $pelanggaranSiswa->save();

                if (!empty($siswa->id_wali_murid)) {
                    $wali_murid = WaliMurid::find($siswa->id_wali_murid);

                    $token_wali_murid = $wali_murid->pengguna->api_token;
                    if (!empty($token_wali_murid)) {
                        $message = 'Putra/Putri Anda melanggar peraturan sekolah';
                        $send_data = array(
                            'title' => 'Informasi',
                            'body' => $message,
                            'priority' => 'high',
                            'screen1' => 'MainMenu',
                            'screen2' => 'MainMenu'
                        );

                        $notifikasi = array(
                            'id' => auth_data()->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_pengguna' => $wali_murid->pengguna->id_pengguna,
                            'id_sekolah' => $wali_murid->pengguna->id_sekolah,
                            'isi_notifikasi' => $message,
                            'created_by' => auth_data()->pengguna->id_pengguna
                        );

                        LibGlobal::sendNotification($token_wali_murid, $send_data, $notifikasi);
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-pelanggaran',
                    'message' => 'Save Pelanggaran Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {
                $siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first();

                // make object to find id
                $pelanggaranSiswa = PelanggaranSiswa::find($id);
                $pelanggaranSiswa->id_siswa = $input->id_siswa;
                $pelanggaranSiswa->id_kelas = $siswa->id_kelas;
                $pelanggaranSiswa->id_semester = $input->id_semester;
                $pelanggaranSiswa->id_subkategori_pelanggaran = $input->id_subkategori_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran = $input->catatan_pelanggaran;
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran = date_format(date_create($input->tgl_pelanggaran), "Y-m-d H:i:s");
                $pelanggaranSiswa->updated_by = auth_data()->pengguna->id_pengguna;
                $pelanggaranSiswa->updated_at = $now;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-pelanggaran',
                    'message' => 'Update Pelanggaran Siswa Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($tindakanPelanggaran = TindakanPelanggaran::where('id_pelanggaran_siswa', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Pelanggaran Siswa'
                    ];
                } else {
                    // make object to find id
                    $pelanggaranSiswa = PelanggaranSiswa::find($id);
                    $pelanggaranSiswa->deleted_by = auth_data()->pengguna->id_pengguna;
                    $pelanggaranSiswa->save();

                    $pelanggaranSiswa->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Pelanggaran Siswa Successfully'
                    ];
                }
            }
        }
    }

    public function actionTindakanPelanggaran(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            /*'id_pelanggaran_siswa'          => 'required',
            'id_presensi_mp_pelanggaran'    => 'required',*/
            'id_jenis_tindakan' => 'required',
            'catatan_tindakan_pelanggaran' => 'required',
            /*'catatan_tindakan_pelanggaran_khusus'    => 'required',*/
            'tgl_tindakan_pelanggaran' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add-nonkbm') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $tindakanPelanggaran = new TindakanPelanggaran;
                $tindakanPelanggaran->id_tindakan_pelanggaran = $id;
                $tindakanPelanggaran->id_pelanggaran_siswa = $input->id_pelanggaran_siswa;
                $tindakanPelanggaran->id_jenis_tindakan = $input->id_jenis_tindakan;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran = $input->catatan_tindakan_pelanggaran;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran_khusus = $input->catatan_tindakan_pelanggaran_khusus;
                // convert format date
                $tindakanPelanggaran->tgl_tindakan_pelanggaran = date_format(date_create($input->tgl_tindakan_pelanggaran), "Y-m-d H:i:s");
                $tindakanPelanggaran->aktor_input_tindakan_pelanggaran = 1;
                $tindakanPelanggaran->created_by = auth_data()->pengguna->id_pengguna;
                $tindakanPelanggaran->save();

                $pelanggaranSiswa = PelanggaranSiswa::find($input->id_pelanggaran_siswa);
                $pelanggaranSiswa->is_sudah_tindakan = 1;
                $pelanggaranSiswa->updated_by = auth_data()->pengguna->id_pengguna;
                $pelanggaranSiswa->updated_at = $now;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/tindakan-pelanggaran',
                    'message' => 'Save Tindakan Pelanggaran Siswa Successfully'
                ];
            } elseif ($mode == 'add-kbm') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $tindakanPelanggaran = new TindakanPelanggaran;
                $tindakanPelanggaran->id_tindakan_pelanggaran = $id;
                $tindakanPelanggaran->id_presensi_mp_pelanggaran = $input->id_presensi_mp_pelanggaran;
                $tindakanPelanggaran->id_jenis_tindakan = $input->id_jenis_tindakan;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran = $input->catatan_tindakan_pelanggaran;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran_khusus = $input->catatan_tindakan_pelanggaran_khusus;
                // convert format date
                $tindakanPelanggaran->tgl_tindakan_pelanggaran = date_format(date_create($input->tgl_tindakan_pelanggaran), "Y-m-d H:i:s");
                $tindakanPelanggaran->aktor_input_tindakan_pelanggaran = 1;
                $tindakanPelanggaran->created_by = auth_data()->pengguna->id_pengguna;
                $tindakanPelanggaran->save();

                $presensiMpPelanggaran = PresensiMpPelanggaran::find($input->id_presensi_mp_pelanggaran);
                $presensiMpPelanggaran->is_sudah_tindakan = 1;
                $presensiMpPelanggaran->updated_by = auth_data()->pengguna->id_pengguna;
                $presensiMpPelanggaran->updated_at = $now;
                $presensiMpPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/tindakan-pelanggaran',
                    'message' => 'Save Tindakan Pelanggaran Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $tindakanPelanggaran = TindakanPelanggaran::find($id);
                $tindakanPelanggaran->id_jenis_tindakan = $input->id_jenis_tindakan;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran = $input->catatan_tindakan_pelanggaran;
                if (!empty($input->catatan_tindakan_pelanggaran_khusus)) {
                    $tindakanPelanggaran->catatan_tindakan_pelanggaran_khusus = $input->catatan_tindakan_pelanggaran_khusus;
                }
                // convert format date
                $tindakanPelanggaran->tgl_tindakan_pelanggaran = date_format(date_create($input->tgl_tindakan_pelanggaran), "Y-m-d H:i:s");
                $tindakanPelanggaran->updated_by = auth_data()->pengguna->id_pengguna;
                $tindakanPelanggaran->updated_at = $now;
                $tindakanPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/tindakan-pelanggaran',
                    'message' => 'Update Tindakan Pelanggaran Siswa Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($tindakanPelanggaran = TindakanPelanggaran::where('id_tindakan_pelanggaran', $id)->where('created_by', '<>', auth_data()->pengguna->id_pengguna)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Tindakan Pelanggaran Siswa, Yg boleh menghapus hanya penindak'
                    ];
                } else {
                    // make object to find id
                    $tindakanPelanggaran = TindakanPelanggaran::find($id);
                    if ($tindakanPelanggaran->id_pelanggaran_siswa) {
                        $pelanggaranSiswa = PelanggaranSiswa::find($tindakanPelanggaran->id_pelanggaran_siswa);
                        $pelanggaranSiswa->is_sudah_tindakan = 0;
                        $pelanggaranSiswa->updated_by = auth_data()->pengguna->id_pengguna;
                        $pelanggaranSiswa->updated_at = $now;
                        $pelanggaranSiswa->save();
                    } elseif ($tindakanPelanggaran->id_presensi_mp_pelanggaran) {
                        $presensiMpPelanggaran = PresensiMpPelanggaran::find($tindakanPelanggaran->id_presensi_mp_pelanggaran);
                        $presensiMpPelanggaran->is_sudah_tindakan = 0;
                        $presensiMpPelanggaran->updated_by = auth_data()->pengguna->id_pengguna;
                        $presensiMpPelanggaran->updated_at = $now;
                        $presensiMpPelanggaran->save();
                    }

                    $tindakanPelanggaran->deleted_by = auth_data()->pengguna->id_pengguna;
                    $tindakanPelanggaran->save();

                    $tindakanPelanggaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Tindakan Pelanggaran Siswa Successfully'
                    ];
                }
            }
        }
    }

    public function deleteInputPelanggaran($id, Request $request)
    {
        $pelanggaranSiswa = PelanggaranSiswa::where('id_pelanggaran_siswa', '=', $id);
        $pelanggaranSiswa->delete();

        return [
            'status' => 203, // SUCCESS AND LOAD TABLE
            'message' => 'Delete Tindakan Pelanggaran Siswa Successfully'
        ];
    }
}
