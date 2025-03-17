<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Jurusan as Jurusan;
use App\Models\Jalur as Jalur;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Kelas as Kelas;
use Illuminate\Support\Collection;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Kota;
use App\Models\PengambilanMp;
use App\Models\Pengguna;
use App\Models\Provinsi;
use App\Models\Semester;
use App\Models\WaliMurid;
use Auth;
use DB;
use Session;
use Validator;

class DataSiswaController extends BaseController
{
    public function viewDataSiswa(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $jurusan = Jurusan::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $jalur = Jalur::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $status_pengguna = StatusPengguna::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->where('status_join_table', '=', 3)
            ->orderBy('nm_status_pengguna')
            ->get();
        $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')
            ->distinct()
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('thn_masuk_siswa', 'ASC')->get();

        return view('pendidikan/siswa/data-siswa/view-data-siswa', compact('auth_data', 'jurusan', 'jalur', 'status_pengguna', 'thn_masuk_siswa'));
    }

    public function getKelas($id_jurusan)
    {
        $kelas = Kelas::where('is_aktif', 1)->where('id_jurusan', '=', $id_jurusan)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        return response()->json($kelas);
    }

    public function actionViewDataSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $validator = Validator::make($request->all(), []);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'siswa/data-siswa/view-detail-data-siswa/' . $input->id_jurusan . '/' . $input->id_kelas . '/' . $input->thn_masuk_siswa . '/' . $input->id_jalur . '/' . $input->id_status_pengguna . '/' . $input->filter_by
            ];
        }
    }

    public function viewDetailDataSiswa(Request $request, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna, $filter_by)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);

        $filter_siswa = new Collection();
        $collection = collect($siswa->all());
        $collection->filter(function ($item) use ($filter_by, $filter_siswa) {
            $hurufPertama = substr($item->nm_pengguna, 0, 1);

            if ($hurufPertama == $filter_by) {
                $filter_siswa->push($item);
            }
        });

        $jurusan = Jurusan::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $kelas = Kelas::where('is_aktif', 1)->where('id_jurusan', '=', $id_jurusan)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $jalur = Jalur::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();
        $status_pengguna = StatusPengguna::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->where('status_join_table', '=', 3)
            ->get();
        $thn_masuk_siswa_list = Siswa::select('thn_masuk_siswa')
            ->distinct()
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('thn_masuk_siswa', 'ASC')->get();

        if ($filter_by == '0') {
            return view('pendidikan/siswa/data-siswa/view-detail-data-siswa', compact('auth_data', 'id_jurusan', 'id_kelas', 'thn_masuk_siswa', 'id_jalur', 'id_status_pengguna', 'jurusan', 'kelas', 'jalur', 'status_pengguna', 'thn_masuk_siswa_list', 'siswa'));
        } else {
            return view('pendidikan/siswa/data-siswa/view-detail-data-siswa', compact('auth_data', 'id_jurusan', 'id_kelas', 'thn_masuk_siswa', 'id_jalur', 'id_status_pengguna', 'jurusan', 'kelas', 'jalur', 'status_pengguna', 'thn_masuk_siswa_list', 'filter_siswa'));
        }
    }
    public function datatablesDataSiswa(Request $request, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);

        $filter_siswa = new Collection();
        $collection = collect($siswa->all());
        $collection->filter(function ($item) use ($input, $filter_siswa) {
            $hurufPertama = substr($item->nm_pengguna, 0, 1);

            if ($hurufPertama == $input->filter_by) {
                $filter_siswa->push($item);
            }
        });

        if ($input->filter_by == '0') {
            return Datatables::of($siswa)
                ->addColumn('nomor_pendaftaran', function ($item) {
                    return $item->kode_voucher;
                })
                ->addColumn('thn_masuk_siswa', function ($item) {
                    return $item->thn_masuk_siswa;
                })
                ->addColumn('mutasi_keluar', function ($item) {
                    return '';
                })
                ->addColumn('mutasi_masuk', function ($item) {
                    return [
                        'nm_sekolah' => $item->nm_sekolah_mutasi,
                        'link_google_drive' => $item->link_google_drive,
                    ];
                })
                ->addColumn('alamat', function ($item) {
                    return $item->alamat_jalan . " Dusun " . $item->alamat_dusun . " Kelurahan " . $item->alamat_kelurahan . " RT" . $item->alamat_rt . " RW" . $item->alamat_rw . " Kecamatan " . $item->alamat_kec . " Kodepos: " . $item->alamat_kodepos . " Kota " . $item->nm_kota . " Provinsi " . $item->nm_provinsi;
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->nis_siswa,
                    );
                    return $data;
                })
                ->make(true);
        } else {
            return Datatables::of($filter_siswa)
                ->addColumn('nomor_pendaftaran', function ($item) {
                    return $item->kode_voucher;
                })
                ->addColumn('thn_masuk_siswa', function ($item) {
                    return $item->thn_masuk_siswa;
                })
                ->addColumn('alamat', function ($item) {
                    return $item->alamat_jalan . " Dusun " . $item->alamat_dusun . " Kelurahan " . $item->alamat_kelurahan . " RT" . $item->alamat_rt . " RW" . $item->alamat_rw . " Kecamatan " . $item->alamat_kec . " Kodepos: " . $item->alamat_kodepos . " Kota " . $item->nm_kota . " Provinsi " . $item->nm_provinsi;
                })->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->nis_siswa,
                    );
                    return $data;
                })
                ->make(true);
        }
    }
    public function viewDetailSiswa(Request $request, $nis_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa1 = Siswa::where('nis_siswa', $nis_siswa)->first();
        $emailSiswa = Pengguna::where('id_pengguna', $siswa1->id_pengguna)->first();
        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_siswa);
        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', '1')->first();

        if ($emailSiswa->email_pengguna == null) {
            $email_pengguna = " ";
        } else {

            $email_pengguna = $emailSiswa->email_pengguna;
        }

        if ($siswa->jenis_kelamin == "1") {
            $jenis_kelamin = "Laki-Laki";
        } elseif ($siswa->jenis_kelamin == "2") {
            $jenis_kelamin = "Perempuan";
        } else {
            $jenis_kelamin = " ";
        }

        if ($siswa->id_kota_lahir == null) {
            $kota_lahir = " ";
        } else {
            $kota_lahir = Kota::select('nm_kota')->where('id_kota', '=', $siswa->id_kota_lahir)->first();
            $kota_lahir = $kota_lahir->nm_kota;
        }

        if ($siswa->alamat_jalan == null) {
            $alamat_jalan_siswa = '';
        } else {
            $alamat_jalan_siswa = "Jalan " . $siswa->alamat_jalan;
        }

        if ($siswa->alamat_dusun == null) {
            $alamat_dusun_siswa = '';
        } else {
            $alamat_dusun_siswa = "Dusun " . $siswa->alamat_dusun;
        }

        if ($siswa->alamat_kelurahan == null) {
            $alamat_kelurahan_siswa = '';
        } else {
            $alamat_kelurahan_siswa = "Kelurahan " . $siswa->alamat_kelurahan;
        }

        if ($siswa->alamat_rt == null) {
            $alamat_rt_siswa = '';
        } else {
            $alamat_rt_siswa = "RT." . $siswa->alamat_rt;
        }

        if ($siswa->alamat_rw == null) {
            $alamat_rw_siswa = '';
        } else {
            $alamat_rw_siswa = "RW." . $siswa->alamat_rw;
        }

        if ($siswa->alamat_kecamatan == null) {
            $alamat_kecamatan_siswa = '';
        } else {
            $alamat_kecamatan_siswa = "Kecamatan " . $siswa->alamat_kecamatan;
        }

        if ($siswa->alamat_kodepos == null) {
            $alamat_kodepos_siswa = '';
        } else {
            $alamat_kodepos_siswa = "Kodepos " . $siswa->alamat_kodepos;
        }

        if ($siswa->alamat_jalan_ortu == null) {
            $alamat_jalan_ortu = '';
        } else {
            $alamat_jalan_ortu = "Jalan " . $siswa->alamat_ortu;
        }

        if ($siswa->alamat_dusun_ortu == null) {
            $alamat_dusun_ortu = '';
        } else {
            $alamat_dusun_ortu = "Dusun " . $siswa->alamat_dusun_ortu;
        }

        if ($siswa->alamat_kelurahan_ortu == null) {
            $alamat_kelurahan_ortu = '';
        } else {
            $alamat_kelurahan_ortu = "Kelurahan " . $siswa->alamat_kelurahan_ortu;
        }

        if ($siswa->almat_rt_ortu == null) {
            $alamat_rt_ortu = '';
        } else {
            $alamat_rt_ortu = "RT." . $siswa->almat_rt_ortu;
        }

        if ($siswa->alamat_rw_ortu == null) {
            $alamat_rw_ortu = '';
        } else {
            $alamat_rw_ortu = "RW." . $siswa->alamat_rw_ortu;
        }

        if ($siswa->alamat_kecamatan_ortu == null) {
            $alamat_kecamatan_ortu = '';
        } else {
            $alamat_kecamatan_ortu = "Kecamatan " . $siswa->alamat_kecamatan_ortu;
        }

        if ($siswa->alamat_kodepos_ortu == null) {
            $alamat_kodepos_ortu = '';
        } else {
            $alamat_kodepos_ortu = "Kodepos " . $siswa->alamat_kodepos_ortu;
        }

        if ($siswa->alamat_kota_ortu == null) {
            $alamat_kota_ortu = "";
        } else {
            $alamat_kota_ortu = Kota::where('id_kota', '=', $siswa->alamat_kota_ortu)->first();
            $alamat_kota_ortu = $alamat_kota_ortu->nm_kota;
        }

        if ($siswa->alamat_provinsi_ortu == null) {
            $alamat_provinsi_ortu = "";
        } else {
            $alamat_provinsi_ortu = Provinsi::where('id_provinsi', '=', $siswa->alamat_provinsi_ortu)->first();
            $alamat_provinsi_ortu = $alamat_provinsi_ortu->nm_provinsi;
        }

        $pengambilan = PengambilanMp::select('pengambilan_mp.nilai_angka', 'pengambilan_mp.nilai_huruf', 'kelas_mp.nm_kelas_mp', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nilai_kkm', 'mata_pelajaran.kredit_semester', 'semester.nm_semester', 'semester.tahun_ajaran')
            ->join('siswa', 'pengambilan_mp.id_siswa', '=', 'siswa.id_siswa')
            ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'pengambilan_mp.id_kelas_mp')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('semester', 'semester.id_semester', '=', 'pengambilan_mp.id_semester')
            ->where('siswa.nis_siswa', '=', $nis_siswa)
            ->where('pengambilan_mp.status_apv_pengambilan_mp', '=', '1')
            ->where('pengambilan_mp.id_semester', $semester_aktif->id_semester)
            ->get();

        $grup_semester_kelas = $pengambilan->groupBy('tahun_ajaran')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester');
        });


        $aktivitas = LibSiswa::aktivitasAdmisi($auth_data, $nis_siswa);
        $wali_murid = WaliMurid::where('nomor_hp_wali_murid', $siswa->nomor_hp_ortu)->first();

        return view('pendidikan/siswa/cari-siswa/view-detail-siswa-cari-siswa', compact('auth_data', 'nis_siswa',  'siswa', 'jenis_kelamin', 'kota_lahir', 'alamat_jalan_siswa', 'alamat_dusun_siswa', 'alamat_kelurahan_siswa', 'alamat_rt_siswa', 'alamat_rw_siswa', 'alamat_kecamatan_siswa', 'alamat_kodepos_siswa', 'alamat_jalan_ortu', 'alamat_dusun_ortu', 'alamat_kelurahan_ortu', 'alamat_rt_ortu', 'alamat_rw_ortu', 'alamat_kecamatan_ortu', 'alamat_kodepos_ortu', 'alamat_kota_ortu', 'alamat_provinsi_ortu', 'aktivitas', 'grup_semester_kelas', 'email_pengguna', 'wali_murid'));
    }
}
