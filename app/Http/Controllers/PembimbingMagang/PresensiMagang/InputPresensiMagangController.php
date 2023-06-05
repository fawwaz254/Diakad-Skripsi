<?php

namespace App\Http\Controllers\PembimbingMagang\PresensiMagang;

use App\Http\Controllers\Controller;
use App\Models\PembimbingMagang;
use App\Models\PengambilanMagang;
use App\Models\PresensiMagang;
use App\Models\PresensiMagangSiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;


use Auth;
use DB;
use Session;
use Validator;

class InputPresensiMagangController extends Controller
{
    public function viewInputPresensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $detail_magang = PembimbingMagang::with('rekanan', 'periode')->where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        return view(
            'pembimbing-magang/presensi-magang/input-presensi-magang/view-input-presensi-magang',
            compact('auth_data', 'detail_magang')
        );
    }

    public function datatablesInputPresensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $id_pengguna = $auth_data->pengguna->id_pengguna;
        $list_data = PresensiMagang::whereHas('pembimbingMagang', function ($query) use ($id_pengguna) {
            $query->where('id_pengguna', '=', $id_pengguna);
        })->orderBy('created_at', 'desc');

        return Datatables::of($list_data)
            ->addColumn('tanggal', function ($item) {
                $date = Carbon::parse($item->tanggal)->locale('id');
                $date->settings(['formatFunction' => 'translatedFormat']);
                return $date->format('l, j F Y '); // Selasa, 16 Maret 2021 ; 08:27 pagi

            })
            ->addColumn('kehadiran', function ($item) {
                $hadir = $item->presensiMagangSiswa->where('kehadiran', 1)->count();
                $sakit_izin = $item->presensiMagangSiswa->whereIn('kehadiran', [2, 3])->count();
                $alfa = $item->presensiMagangSiswa->where('kehadiran', 0)->count();
                $data = array(
                    'hadir' => $hadir,
                    'sakit_izin' => $sakit_izin,
                    'alfa' => $alfa
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_presensi_magang,
                );
                return $data;
            })
            ->make(true);
    }

    public function viewAddEditInputPresensiMagang(Request $request, $id_presensi_magang)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($id_presensi_magang != '0') {
            $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->find($id_presensi_magang);
        } else {
            $presensi_magang = null;
        }

        $detail_magang = PembimbingMagang::with('rekanan', 'periode')->where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        return view(
            'pembimbing-magang/presensi-magang/input-presensi-magang/add-edit-input-presensi-magang',
            compact('auth_data', 'detail_magang', 'presensi_magang')
        );
    }


    public function datatablesSiswaInputPresensiMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($input->id_presensi_magang != '0') {
            $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->find($input->id_presensi_magang);
        } else {
            $presensi_magang = null;
        }

        $pembimbing_magang = PembimbingMagang::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $list_data = PengambilanMagang::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna')
            ->where('id_periode_magang', $pembimbing_magang->id_periode_magang)->where('id_rekanan_magang', $pembimbing_magang->id_rekanan_magang);

        return Datatables::of($list_data)
            ->editColumn('nis_siswa', function ($item) {
                $data = array(
                    'id_siswa' => $item->siswa->id_siswa,
                    'nis_siswa' => $item->siswa->nis_siswa,
                    'status_pengguna' => array(
                        'status' => $item->siswa->pengguna->status_pengguna->aktif_status_pengguna,
                        'nm_status' => $item->siswa->pengguna->status_pengguna->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->addColumn('keterangan', function ($item) use ($presensi_magang) {
                $kehadiran = null;
                if ($presensi_magang && $selected_presensi_ekskul_peserta = $presensi_magang->presensiMagangSiswa->firstWhere('id_siswa', $item->siswa->id_siswa)) {
                    $kehadiran = $selected_presensi_ekskul_peserta->kehadiran;
                }
                $options = array(
                    array('id' => 1, 'text' => 'Hadir'),
                    array('id' => 2, 'text' => 'Sakit'),
                    array('id' => 3, 'text' => 'Izin'),
                    array('id' => 0, 'text' => 'Alpa'),
                );
                $data = array(
                    'options' => $options,
                    'kehadiran' => $kehadiran,
                    'status_pengguna' => array(
                        'status' => $item->siswa->pengguna->status_pengguna->aktif_status_pengguna,
                        'nm_status' => $item->siswa->pengguna->status_pengguna->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->make(true);
    }

    public function actionInputAbsensiMagang(Request $request, $mode, $id_presensi_magang)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if ($mode == 'add') {
                DB::beginTransaction();
                try {
                    $tanggal = Carbon::parse($input->tanggal);
                    $pembimbing_magang = PembimbingMagang::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
                    if ($presensi = PresensiMagang::where('tanggal', $tanggal)->where('id_pembimbing_magang', $pembimbing_magang->id_pembimbing_magang)->first()) { } else {
                        $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $presensi = new PresensiMagang;
                        $presensi->id_presensi_magang = $id;
                        $presensi->created_by = $auth_data->pengguna->id_pengguna;
                        $presensi->id_pembimbing_magang = $pembimbing_magang->id_pembimbing_magang;
                    }
                    $presensi->tanggal = $tanggal;
                    $presensi->keterangan = $input->keterangan;
                    $presensi->save();

                    $array_combine = array();
                    foreach ($input->id_siswa as $key => $id_siswa) {
                        $array_combine[] = (object) array(
                            'id_siswa'    => $input->id_siswa[$key],
                            'kehadiran'      => $input->kehadiran[$key]
                        );
                    }

                    // presensi_ekskul_peserta
                    foreach ($array_combine as $item) {
                        if ($presensi_siswa = PresensiMagangSiswa::where('id_presensi_magang', '=', $presensi->id_presensi_magang)->where('id_siswa', '=', $item->id_siswa)->first()) { } else {
                            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $presensi_siswa                                 = new PresensiMagangSiswa;
                            $presensi_siswa->id_presensi_magang_siswa       = $id;
                            $presensi_siswa->id_presensi_magang             = $presensi->id_presensi_magang;
                            $presensi_siswa->created_by                    = $input->auth_data->pengguna->id_pengguna;
                            $presensi_siswa->id_siswa                    = $item->id_siswa;
                        }

                        $presensi_siswa->kehadiran                   = $item->kehadiran;
                        $presensi_siswa->save();
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi-magang/input-presensi-magang',
                        'message' => 'Save Absensi Magang Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' =>  $e->getMessage()

                    ];
                }
            } elseif ($mode = 'edit') {
                DB::beginTransaction();
                try {
                    $tanggal = Carbon::parse($input->tanggal);
                    $pembimbing_magang = PembimbingMagang::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
                    if ($presensi = PresensiMagang::find($id_presensi_magang)) {
                        $presensi->tanggal = $tanggal;
                        $presensi->keterangan = $input->keterangan;
                        $presensi->save();
                    }

                    $array_combine = array();
                    foreach ($input->id_siswa as $key => $id_siswa) {
                        $array_combine[] = (object) array(
                            'id_siswa'    => $input->id_siswa[$key],
                            'kehadiran'      => $input->kehadiran[$key]
                        );
                    }

                    // presensi_ekskul_peserta
                    foreach ($array_combine as $item) {
                        if ($presensi_siswa = PresensiMagangSiswa::where('id_presensi_magang', '=', $id_presensi_magang)->where('id_siswa', '=', $item->id_siswa)->first()) {
                            $presensi_siswa->kehadiran              = $item->kehadiran;
                            $presensi_siswa->updated_by             = $auth_data->pengguna->id_pengguna;
                            $presensi_siswa->save();
                        }
                    }

                    DB::commit();
                    // all good
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi-magang/input-presensi-magang',
                        'message' => 'Update Absensi Magang Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' =>  $e->getMessage()

                    ];
                }
            } elseif ($mode = 'delete') { } else { }
        }
    }
    public function viewDetailInputPresensiMagang(Request $request, $id_presensi_magang)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pembimbing_magang = PembimbingMagang::with('rekanan', 'periode')->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $presensi_magang = PresensiMagang::with('presensiMagangSiswa')->find($id_presensi_magang);

        $data_siswa = PengambilanMagang::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna')->where('id_periode_magang', $pembimbing_magang->id_periode_magang)->where('id_rekanan_magang', $pembimbing_magang->id_rekanan_magang)->get();

        return view(
            'pembimbing-magang/presensi-magang/input-presensi-magang/view-detail-input-presensi-magang',
            compact('auth_data', 'presensi_magang', 'data_siswa', 'pembimbing_magang')
        );
    }
}
