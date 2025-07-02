<?php

namespace App\Http\Controllers\Guru\Biodata;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\KegiatanGuru;
use App\Models\TingkatPrestasiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class DataKegiatanController extends BaseController
{

    public function viewDataKegiatan(Request $request)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('guru/biodata/data-kegiatan/view-data-kegiatan', compact('auth_data'));
    }

    public function viewAddDataKegiatan(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        $guru = LibGuru::fetchDataAllGuru($auth_data);

        return view('guru/biodata/data-kegiatan/add-data-kegiatan', compact('auth_data', 'tingkat', 'guru'));
    }

    public function viewEditDataKegiatan(Request $request, $id)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        $kegiatan = KegiatanGuru::find($id);
        $guru = LibGuru::fetchDataAllGuru($auth_data);

        return view('guru/biodata/data-kegiatan/edit-data-kegiatan', compact('auth_data', 'tingkat', 'kegiatan', 'guru'));
    }

    public function actionDataKegiatan(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();
        $role = $auth_data->role_aktif->nm_role;

        $validator = Validator::make($request->all(), [
            'nm_kegiatan' => 'required',
            'lokasi' => 'required',
            'penyelenggara' => 'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'tgl_mulai_kegiatan' => 'required',
            'tgl_berakhir_kegiatan' => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {

                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $kegiatan = new KegiatanGuru;

                if ($role == 'Humas') {
                    $kegiatan->id_pengguna = $input->id_pengguna;
                    $kegiatan->status = 1;
                    $path = 'data-guru/data-kegiatan';
                } else {
                    $kegiatan->id_pengguna = $auth_data->pengguna->id_pengguna;
                    $kegiatan->status = 0;
                    $path = 'biodata/data-kegiatan';
                }

                $kegiatan->id_kegiatan_guru = $id;
                $kegiatan->nm_kegiatan = $input->nm_kegiatan;
                $kegiatan->lokasi = $input->lokasi;
                $kegiatan->penyelenggara = $input->penyelenggara;
                $kegiatan->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                $kegiatan->tgl_mulai_kegiatan = date("Y-m-d", strtotime($input->tgl_mulai_kegiatan));
                $kegiatan->tgl_berakhir_kegiatan = date("Y-m-d", strtotime($input->tgl_berakhir_kegiatan));
                $kegiatan->link_kegiatan = $input->link_kegiatan;
                $kegiatan->created_by = auth_data()->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => $path,
                    'message' => 'Save Kegiatan Successfully'
                ];
            } elseif ($mode == 'edit') {

                $kegiatan = KegiatanGuru::find($id);

                if ($role == 'Humas') {
                    $kegiatan->id_pengguna = $input->id_pengguna;
                    $path = 'data-guru/data-kegiatan/edit/' . $id;
                    $kegiatan->status = $input->status;
                } else {
                    $path = 'biodata/data-kegiatan/edit/' . $id;
                }

                $kegiatan->nm_kegiatan = $input->nm_kegiatan;
                $kegiatan->lokasi = $input->lokasi;
                $kegiatan->penyelenggara = $input->penyelenggara;
                $kegiatan->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                $kegiatan->tgl_mulai_kegiatan = date("Y-m-d", strtotime($input->tgl_mulai_kegiatan));
                $kegiatan->tgl_berakhir_kegiatan = date("Y-m-d", strtotime($input->tgl_berakhir_kegiatan));
                $kegiatan->link_kegiatan = $input->link_kegiatan;
                $kegiatan->updated_at = $now;
                $kegiatan->updated_by = auth_data()->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => $path,
                    'message' => 'Edit Kegiatan Successfully'
                ];
            } elseif ($mode == 'delete') {

                $kegiatan = KegiatanGuru::find($id);
                $kegiatan->deleted_by  = auth_data()->pengguna->id_pengguna;
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

    public function datatablesDataKegiatan(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $role = $auth_data->role_aktif->nm_role;

        $list_data = KegiatanGuru::Select(
            'kegiatan_guru.id_kegiatan_guru',
            'kegiatan_guru.nm_kegiatan',
            'kegiatan_guru.status',
            'kegiatan_guru.tgl_mulai_kegiatan',
            'kegiatan_guru.tgl_berakhir_kegiatan',
            'kegiatan_guru.lokasi',
            'kegiatan_guru.penyelenggara',
            'kegiatan_guru.link_kegiatan',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            'kegiatan_guru.keterangan',
            'pengguna.nm_pengguna'
        )
            ->join('pengguna', 'kegiatan_guru.id_pengguna', 'pengguna.id_pengguna')
            ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'kegiatan_guru.id_tingkat_prestasi_siswa')
            ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        if ($role != 'Humas') {
            $list_data = $list_data->where('kegiatan_guru.id_pengguna', '=', $auth_data->pengguna->id_pengguna);
        }

        return Datatables::of($list_data)
            ->addColumn('tgl_kegiatan', function ($item) {
                if ($item->tgl_mulai_kegiatan == $item->tgl_berakhir_kegiatan || empty($item->tgl_berakhir_kegiatan)) {
                    return Carbon::parse($item->tgl_mulai_kegiatan)->translatedFormat('d M Y');
                } else {
                    return Carbon::parse($item->tgl_mulai_kegiatan)->translatedFormat('d M Y') . ' - ' . Carbon::parse($item->tgl_berakhir_kegiatan)->translatedFormat('d M Y');
                }
            })
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
                    'id' => $item->id_kegiatan_guru,
                    'link_kegiatan' => $item->link_kegiatan,
                    'status' => $item->status
                );
                return $data;
            })
            ->make(true);
    }
}
