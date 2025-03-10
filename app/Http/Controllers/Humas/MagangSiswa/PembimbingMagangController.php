<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Imports\DataImportExcel;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengambilanMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\PembimbingMagang;
use App\Models\PresensiMagang;
use App\Models\PresensiMagangSiswa;
use App\Models\RekananMagang;
use App\Models\RolePengguna;
use App\Models\Semester;
use Illuminate\Support\Facades\Hash;
use Auth;
use Excel;
use DB;
use Session;
use Validator;


class PembimbingMagangController extends Controller
{
    public function viewPembimbingMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);
        $semester_aktif =  Semester::where('is_aktif_semester', '1')->first();
        return view('humas/magang-siswa/pembimbing-magang/view-pembimbing-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang', 'semester_aktif'));
    }

    public function addPembimbingMagang(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_pengambil_magang = PengambilanMagang::with('periode.semester', 'rekanan')->find($id);

        do {
            $randomNumber = mt_rand(10000000, 99999999);
            $randomString = strval($randomNumber);
            $pengguna = Pengguna::where('username', $randomString)->first();
        } while ($pengguna);

        return view('humas/magang-siswa/pembimbing-magang/add-pembimbing-magang', compact('auth_data', 'data_pengambil_magang', 'randomString'));
    }

    public function editPembimbingMagang(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $pembimbing_magang = PembimbingMagang::with('periode.semester', 'rekanan')->find($id);
        return view('humas/magang-siswa/pembimbing-magang/edit-pembimbing-magang', compact('auth_data', 'pembimbing_magang'));
    }


    public function datatablesPembimbingMagang(Request $request)
    {

        $input = (object) $request->input();
        // $auth_data = auth_data();

        $id_periode_magang = $input->id_periode_magang;

        $data_pengambilan_magang = PengambilanMagang::where('id_periode_magang', $id_periode_magang)->get()->toArray();
        $id_pengambilan_magang =  collect($data_pengambilan_magang)->unique('id_rekanan_magang')->pluck('id_pengambilan_magang');

        $data = PengambilanMagang::whereIn('id_pengambilan_magang', $id_pengambilan_magang)->with('periode.semester', 'rekanan', 'pembimbingMagang.pengguna');
        // dd($data[1]);
        return Datatables::of($data)
            ->addColumn('semester', function ($item) {
                return $item->periode->semester->tahun_ajaran . " " . $item->periode->semester->nm_semester;
            })
            ->addColumn('periode', function ($item) {
                return $item->periode->nm_periode_magang;
            })
            ->addColumn('username_pembimbing_magang', function ($item) use ($id_periode_magang) {
                $data =  $item->pembimbingMagang->where('id_periode_magang', $id_periode_magang)->first();
                $username = $data ? $data->pengguna->username : '';
                $nama_pengguna = $data ? $data->pengguna->nm_pengguna : '';
                return [
                    'username' => $username,
                    'pembimbing_magang' => $nama_pengguna
                ];
            })
            ->addColumn('action', function ($item) use ($id_periode_magang) {
                $data =  $item->pembimbingMagang->where('id_periode_magang', $id_periode_magang)->first();
                $data = array(
                    'id' => $item->id_pengambilan_magang,
                    'pembimbingMagang' => $data ? $data->id_pembimbing_magang : null,
                );

                return $data;
            })
            ->make(true);
    }

    public function actionInputPembimbingMagang(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'nm_pembimbing_magang'     => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        if ($mode == 'add') {
            $id_pengguna            = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $id_pembimbing_magang   = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $status_pengguna        = StatusPengguna::where('status_join_table', '=', '6')->where('aktif_status_pengguna', '=', '1')->first();

            $pengguna                           = new Pengguna;
            $pengguna->id_pengguna              = $id_pengguna;
            $pengguna->id_status_pengguna       = $status_pengguna->id_status_pengguna;
            $pengguna->id_sekolah               = auth_data()->pengguna->id_sekolah;
            $pengguna->nm_pengguna              = $input->nm_pembimbing_magang;
            $pengguna->username                 = $input->username;
            $pengguna->password                 = Hash::make($input->username);
            $pengguna->must_change_password     = 0;
            $pengguna->status_join_table        = 6;
            $pengguna->created_by               = auth_data()->pengguna->id_pengguna;
            $pengguna->save();

            $pembimbing                                 = new PembimbingMagang;
            $pembimbing->id_pembimbing_magang           = $id_pembimbing_magang;
            $pembimbing->id_periode_magang              = $input->id_periode_magang;
            $pembimbing->id_rekanan_magang              = $input->id_rekanan_magang;
            $pembimbing->id_pengguna                    = $id_pengguna;
            $pembimbing->created_by                     = auth_data()->pengguna->id_pengguna;
            $pembimbing->created_at                     = $now;
            $pembimbing->save();

            $rolePengguna                           = new RolePengguna;
            $rolePengguna->id_role                  = 20;
            $rolePengguna->id_pengguna              = $id_pengguna;
            $rolePengguna->keterangan_role_pengguna = "Input Pembimbing Magang";
            $rolePengguna->is_aktif                 = 1;
            $rolePengguna->created_by               = auth_data()->pengguna->id_pengguna;
            $rolePengguna->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'magang-siswa/pembimbing-magang',
                'message' => 'Save Data Pembimbing Magang Successfully'
            ];
        } elseif ($mode == 'edit') {
            $pembimbing_magang = PembimbingMagang::find($id);
            $pengguna = Pengguna::find($pembimbing_magang->id_pengguna);
            $pengguna->nm_pengguna = $input->nm_pembimbing_magang;
            $pengguna->save();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'magang-siswa/pembimbing-magang',
                'message' => 'Update Data Pembimbing Magang Successfully'
            ];
        } elseif ($mode == 'delete') {
            $pembimbing_magang = PembimbingMagang::find($id);
            $pengguna = Pengguna::find($pembimbing_magang->id_pengguna);
            $role_pengguna = RolePengguna::where('id_pengguna', $pembimbing_magang->id_pengguna)->first();
            $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->where('id_pembimbing_magang', $id)->get();
            if ($presensi_magang) {
                foreach ($presensi_magang as $presensi) {
                    foreach ($presensi->presensiMagangSiswa as $presensiSiswa) {
                        $presensiSiswa->deleted_by = auth_data()->pengguna->id_pengguna;
                        $presensiSiswa->save();
                        $presensiSiswa->delete();
                    }
                    $presensi->deleted_by = auth_data()->pengguna->id_pengguna;
                    $presensi->save();
                    $presensi->delete();
                }
            }
            $role_pengguna->deleted_by = auth_data()->pengguna->id_pengguna;
            $role_pengguna->save();
            $role_pengguna->delete();
            $pengguna->deleted_by = auth_data()->pengguna->id_pengguna;
            $pengguna->save();
            $pengguna->delete();
            $pembimbing_magang->deleted_by =  auth_data()->pengguna->id_pengguna;
            $pembimbing_magang->save();
            $pembimbing_magang->delete();
            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Pembimbing Magang Successfully'
            ];
        }
    }
}
