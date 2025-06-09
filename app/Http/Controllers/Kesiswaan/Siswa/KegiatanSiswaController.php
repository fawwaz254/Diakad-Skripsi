<?php

namespace App\Http\Controllers\Kesiswaan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Siswa as Siswa;
use App\Models\KegiatanSiswa;
use App\Models\TingkatPrestasiSiswa;
use App\Models\Semester as Semester;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\LibGlobal;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class KegiatanSiswaController extends BaseController
{
    public function viewKegiatanSiswa(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('kesiswaan/siswa/kegiatan-siswa/view-kegiatan-siswa', compact('auth_data', 'id_kelas'));
    }

    public function datatablesKegiatanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = KegiatanSiswa::Select(
            'kegiatan_siswa.id_kegiatan_siswa',
            'kegiatan_siswa.nm_kegiatan_siswa',
            'kegiatan_siswa.status',
            'kegiatan_siswa.tgl_kegiatan_siswa',
            'kegiatan_siswa.lokasi_kegiatan_siswa',
            'kegiatan_siswa.penyelenggara_kegiatan_siswa',
            'kegiatan_siswa.nm_kegiatan_scan_sertif',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa'
        )
            ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'kegiatan_siswa.id_tingkat_prestasi_siswa')
            ->join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')
            ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('kegiatan_siswa.status', 1)
            ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan_siswa,
                    'link_sertifikat' => $item->nm_kegiatan_scan_sertif,
                    'status' => $item->status
                );
                return $data;
            })
            ->make(true);
    }

    public function AddKegiatanSiswa(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_semester = Semester::where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return view('kesiswaan/siswa/kegiatan-siswa/add-kegiatan-siswa', compact('auth_data', 'tingkat', 'data_kelas', 'data_semester'));
    }

    public function editKegiatanSiswa(Request $request, $id_kegiatan_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_semester = Semester::where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $tingkat = TingkatPrestasiSiswa::where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        $kegiatan = KegiatanSiswa::join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')->where('id_kegiatan_siswa', '=', $id_kegiatan_siswa)->first();

        // $id_jenis_mata_pelajaran = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/kegiatan-siswa/edit-kegiatan-siswa', compact('auth_data', 'data_kelas', 'data_semester', 'tingkat', 'kegiatan'));
    }

    public function actionKegiatanSiswa(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'nm_kegiatan_siswa' => 'required',
            'id_siswa' => 'required',
            'id_semester' => 'required',
            'lokasi_kegiatan_siswa' => 'required',
            'penyelenggara_kegiatan_siswa' => 'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'tgl_kegiatan_siswa' => 'required',
            'link_sertifikat' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {

                if ($siswa = Siswa::find($input->id_siswa)) {

                    $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                    $kegiatan = new KegiatanSiswa;
                    $kegiatan->id_kegiatan_siswa = $id;
                    $kegiatan->id_siswa = $siswa->id_siswa;
                    $kegiatan->id_kelas = $siswa->id_kelas;
                    $kegiatan->id_semester = $input->id_semester;
                    $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                    $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                    $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
                    $kegiatan->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                    $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
                    $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
                    $kegiatan->created_by = auth_data()->pengguna->id_pengguna;
                    $kegiatan->created_at = $now;
                    $kegiatan->status = 1;
                    $kegiatan->approved_by = $auth_data->pengguna->id_pengguna;
                    $kegiatan->approved_at = $now;
                    $kegiatan->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-kesiswaan/kegiatan-siswa',
                        'message' => 'Save Data Kegiatan Siswa Successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Add Data Kegiatan siswa gagal'
                    ];
                }
            } elseif ($mode == 'edit') {

                if ($siswa = Siswa::find($input->id_siswa)) {

                    $kegiatan = KegiatanSiswa::find($id);
                    if ($input->id_siswa != $kegiatan->id_siswa) {
                        $kegiatan->id_siswa  = $input->id_siswa;
                        $kegiatan->id_kelas  = $siswa->id_kelas;
                    }

                    $kegiatan->id_semester = $input->id_semester;
                    $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                    $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                    $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
                    $kegiatan->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                    $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
                    $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
                    $kegiatan->updated_at = $now;
                    $kegiatan->updated_by = auth_data()->pengguna->id_pengguna;
                    $kegiatan->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-kesiswaan/kegiatan-siswa',
                        'message' => 'Update Data Kegiatan Siswa Successfully'
                    ];
                } else {

                    return [
                        'status' => 300, // FAILED
                        'message' => 'Update Data Kegiatan siswa gagal'
                    ];
                }
            } elseif ($mode == 'delete') {

                $kegiatan                 = KegiatanSiswa::find($id);
                $kegiatan->deleted_by     = auth_data()->pengguna->id_pengguna;
                $kegiatan->save();

                $kegiatan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Prestasi Siswa Successfully'
                ];
            }
        }
    }
}
