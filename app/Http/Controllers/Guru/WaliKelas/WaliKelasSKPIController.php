<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Guru;
use App\Models\Pengguna;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KegiatanSiswa;
use App\Models\Siswa;
use Carbon\Carbon;


use Auth;
use DB;
use Session;
use Validator;

class WaliKelasSKPIController extends Controller
{
    public function viewListSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/view-list-siswa', compact('auth_data'));
    }

    public function datatablesListSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);


        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)->with('pengguna', 'kelas', 'kegiatan_siswa', 'prestasi_siswa', 'informasi_tambahan');

        return Datatables::of($list_siswa)
            ->addColumn('kegiatan_siswa', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                    'count' => $item->kegiatan_siswa->count()
                );
                return $data;
            })
            ->addColumn('prestasi_siswa', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                    'count' => $item->prestasi_siswa->count()
                );
                return $data;
            })->addColumn('informasi_tambahan', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                    'count' => $item->informasi_tambahan->count()
                );
                return $data;
            })
            ->make(true);
    }

    public function viewKegiatanSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/kegiatan-siswa/view-kegiatan-siswa', compact('auth_data', 'id_siswa'));
    }

    public function datatablesKegiatanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KegiatanSiswa::Select(
            'kegiatan_siswa.id_kegiatan_siswa',
            'kegiatan_siswa.nm_kegiatan_siswa',
            'kegiatan_siswa.status',
            'kegiatan_siswa.tgl_kegiatan_siswa',
            'kegiatan_siswa.lokasi_kegiatan_siswa',
            'kegiatan_siswa.penyelenggara_kegiatan_siswa',
            'kegiatan_siswa.nm_kegiatan_scan_sertif',
            'kegiatan_siswa.keterangan'
        )
            ->where('id_siswa', $input->id_siswa)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('keterangan_status', function ($item) {
                if ($item->status == 0) {
                    $status = 'Belum Diapprove';
                    $color = 'pink';
                } elseif ($item->status == 1) {
                    $status = 'Sudah Diapprove';
                    $color = 'teal';
                } elseif ($item->status == 10) {
                    $status = 'Ditolak';
                    $color = 'red';
                }
                $data = array(
                    'status' => $status,
                    'color'  => $color
                );
                return $data;
            })
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

    public function addKegiatanSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/kegiatan-siswa/add-kegiatan-siswa', compact('auth_data', 'id_siswa'));
    }

    public function actionDataKegiatanSiswa(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $id_kelas = Siswa::where('id_siswa', $input->id_siswa)->pluck('id_kelas')->first();
        // dd($id_kelas);
        $validator = Validator::make($request->all(), [
            'nm_kegiatan_siswa' => 'required',
            'lokasi_kegiatan_siswa' => 'required',
            'penyelenggara_kegiatan_siswa' => 'required',
            // 'id_tingkat_prestasi_siswa' => 'required',
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

                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $kegiatan = new KegiatanSiswa;
                $kegiatan->id_kegiatan_siswa = $id;
                $kegiatan->id_siswa = $input->id_siswa;
                $kegiatan->id_kelas = $id_kelas;
                $kegiatan->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
                $kegiatan->id_tingkat_prestasi_siswa = 0;
                $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
                $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
                $kegiatan->created_by = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-skpi-siswa/kegiatan_siswa/' . $input->id_siswa,
                    'message' => 'Save Kegiatan Successfully'
                ];
            } elseif ($mode == 'edit') {
                $kegiatan = KegiatanSiswa::find($id);
                $kegiatan->id_siswa = $input->id_siswa;
                $kegiatan->id_kelas = $id_kelas;
                $kegiatan->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
                $kegiatan->id_tingkat_prestasi_siswa = 0;
                $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
                $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
                $kegiatan->updated_at = $now;
                $kegiatan->updated_by = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'skpi/data-kegiatan-siswa',
                    'message' => 'Edit Kegiatan Successfully'
                ];
            } elseif ($mode == 'delete') {

                $kegiatan = KegiatanSiswa::find($id);
                $kegiatan->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->deleted_at  = $now;
                $kegiatan->save();

                $kegiatan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Kegiatan Successfully'
                ];
            }
        }
    }
}
