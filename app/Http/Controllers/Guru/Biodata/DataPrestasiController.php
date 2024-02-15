<?php

namespace App\Http\Controllers\Guru\Biodata;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Kelas as Kelas;
use App\Models\Semester as Semester;
use App\Models\Siswa as Siswa;
use App\Models\Guru as Guru;
use App\Models\Ekskul as Ekskul;
use App\Models\PrestasiGuru as PrestasiGuru;
use App\Models\TingkatPrestasiSiswa as TingkatPrestasiSiswa;

use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class DataPrestasiController extends BaseController
{
    public function viewDataPrestasi(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/biodata/data-prestasi/view-data-prestasi', compact('auth_data'));
    }

    public function viewAddDataPrestasi(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        $jenis_prestasi = [[1, 'Sains'], [2, 'Seni'], [3, 'Olahraga'], [4, 'Lain-lain']];

        $guru = LibGuru::fetchDataAllGuru($auth_data);

        return view('guru/biodata/data-prestasi/add-data-prestasi', compact('auth_data', 'tingkat', 'jenis_prestasi', 'guru'));
    }

    public function viewEditDataPrestasi(Request $request, $id)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        $jenis_prestasi = [[1, 'Sains'], [2, 'Seni'], [3, 'Olahraga'], [4, 'Lain-lain']];
        $prestasi = PrestasiGuru::findOrFail($id);

        $guru = LibGuru::fetchDataAllGuru($auth_data);

        return view('guru/biodata/data-prestasi/edit-data-prestasi', compact('auth_data', 'tingkat', 'jenis_prestasi', 'prestasi', 'guru'));
    }

    public function actionDataPrestasi(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();
        $role = $auth_data->role_aktif->nm_role;

        $validator = Validator::make($request->all(), [
            'nm_prestasi' => 'required',
            'peringkat' => 'required',
            'lokasi' => 'required',
            'penyelenggara' => 'required',
            'jenis_prestasi' => 'required',
            'jenis_lomba' => 'required',
            'id_tingkat_prestasi' => 'required',
            'tanggal' => 'required',
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

                $prestasi = new PrestasiGuru;
                $prestasi->id_prestasi_guru = $id;

                if ($role == 'Humas') {
                    $prestasi->id_pengguna = $input->id_pengguna;
                    $prestasi->status = 1;
                    $path = 'data-guru/data-prestasi';
                } else {
                    $prestasi->id_pengguna = $auth_data->pengguna->id_pengguna;
                    $prestasi->status = 0;
                    $path = 'biodata/data-prestasi';
                }

                $prestasi->id_tingkat_prestasi = $input->id_tingkat_prestasi;
                $prestasi->jenis_prestasi = $input->jenis_prestasi;
                $prestasi->jenis_lomba = $input->jenis_lomba;
                $prestasi->nm_prestasi = $input->nm_prestasi;
                $prestasi->lokasi = $input->lokasi;
                $prestasi->penyelenggara = $input->penyelenggara;
                $prestasi->peringkat = $input->peringkat;
                $prestasi->tanggal = date("Y-m-d", strtotime($input->tanggal));
                $prestasi->created_by = $input->auth_data->pengguna->id_pengguna;
                $prestasi->link_sertifikat = $input->link_sertifikat;

                $prestasi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => $path,
                    'message' => 'Save Prestasi Successfully'
                ];
            } elseif ($mode == 'edit') {

                $prestasi = PrestasiGuru::findOrFail($id);

                if ($role == 'Humas') {
                    $prestasi->id_pengguna = $input->id_pengguna;
                    $path = 'data-guru/data-prestasi/edit/' . $id;
                    $prestasi->status = $input->status;
                } else {
                    $path = 'biodata/data-prestasi/edit/' . $id;
                }

                $prestasi->id_tingkat_prestasi = $input->id_tingkat_prestasi;
                $prestasi->jenis_prestasi = $input->jenis_prestasi;
                $prestasi->jenis_lomba = $input->jenis_lomba;
                $prestasi->nm_prestasi = $input->nm_prestasi;
                $prestasi->lokasi = $input->lokasi;
                $prestasi->penyelenggara = $input->penyelenggara;
                $prestasi->peringkat = $input->peringkat;
                $prestasi->tanggal = date("Y-m-d", strtotime($input->tanggal));
                $prestasi->link_sertifikat = $input->link_sertifikat;
                $prestasi->updated_at = $now;
                $prestasi->updated_by = $input->auth_data->pengguna->id_pengguna;

                $prestasi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => $path,
                    'message' => 'Edit Prestasi Successfully'
                ];
            } elseif ($mode == 'delete') {

                $prestasi = PrestasiGuru::findOrFail($id);
                $prestasi->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $prestasi->deleted_at  = $now;
                $prestasi->save();

                $prestasi->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Prestasi Successfully'
                ];
            }
        }
    }

    public function datatablesDataPrestasi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $role = $auth_data->role_aktif->nm_role;

        $list_data = PrestasiGuru::select(
            'prestasi_guru.nm_prestasi',
            'prestasi_guru.jenis_prestasi',
            'prestasi_guru.peringkat',
            'prestasi_guru.link_sertifikat',
            'prestasi_guru.status',
            'prestasi_guru.jenis_lomba',
            'prestasi_guru.lokasi',
            'prestasi_guru.penyelenggara',
            'prestasi_guru.id_prestasi_guru',
            'prestasi_guru.tanggal',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            'pengguna.nm_pengguna'

        )
            ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_guru.id_tingkat_prestasi')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'prestasi_guru.id_pengguna')
            ->orderBy('prestasi_guru.created_at', 'desc')
            ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        if ($role != 'Humas') {
            $list_data = $list_data->where('pengguna.id_pengguna', '=', $auth_data->pengguna->id_pengguna);
        }

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
            ->addColumn('jenis_prestasi', function ($item) {
                if ($item->jenis_prestasi == 1) {
                    return "Sains";
                } elseif ($item->jenis_prestasi == 2) {
                    return "Seni";
                } elseif ($item->jenis_prestasi == 3) {
                    return "Olahraga";
                } elseif ($item->jenis_prestasi == 99) {
                    return "Lain-Lain";
                }
            })
            ->addColumn('tanggal', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tanggal));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_prestasi_guru,
                    'link_sertifikat' => $item->link_sertifikat,
                    'status' => $item->status
                );
                return $data;
            })
            ->make(true);
    }
}
