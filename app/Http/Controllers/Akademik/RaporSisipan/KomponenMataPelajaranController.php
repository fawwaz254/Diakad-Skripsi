<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Models\KelasSisipan;
use App\Models\KelompokSisipan;
use App\Models\MataPelajaran;
use App\Models\MataPelajaranSisipan;
use App\Models\SubKelompokSisipan;
use Validator;

class KomponenMataPelajaranController extends Controller
{
    public function viewKomponenMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', '1')->get();
        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/view-komponen-mata-pelajaran', compact('auth_data', 'kelas'));
    }

    public function postKomponenMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Harap untuk memilih terlebih dahulu'
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'rapor-sisipan/komponen-mata-pelajaran/detail/' . $input->id_kelas,
            ];
        }
    }

    public function viewDetailKomponenMataPelajaran(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', '1')->get();
        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/detail-komponen-mata-pelajaran', compact('auth_data', 'kelas', 'id_kelas'));
    }

    public function datatablesKomponenMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $list_kelas_sisipan = KelasSisipan::with('mata_pelajaran_sisipan.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->where('id_kelas', $input->id_kelas)
            ->whereHas('mata_pelajaran_sisipan', function ($q) {
                $q->where('jenis', '1');
            });

        return Datatables::of($list_kelas_sisipan)
            ->addColumn('nm_mata_pelajaran', function ($item) {
                if (isset($item->mata_pelajaran_sisipan->mata_pelajaran)) {
                    return  $item->mata_pelajaran_sisipan->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran . ' - ' . $item->mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                } else {
                    return 'Mapel DIhapus';
                }
            })
            ->addColumn('nm_kelompok_sisipan', function ($item) {
                if ($item->mata_pelajaran_sisipan->kelompok_sisipan) {
                    return $item->mata_pelajaran_sisipan->kelompok_sisipan->nm_kelompok_sisipan;
                } else if ($item->mata_pelajaran_sisipan->sub_kelompok_sisipan) {
                    return  $item->mata_pelajaran_sisipan->sub_kelompok_sisipan->kelompok_sisipan->nm_kelompok_sisipan . " - " . $item->mata_pelajaran_sisipan->sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                } else {
                    return '-';
                }
            })->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kelas_sisipan,

                );
                return $data;
            })
            ->make(true);
    }

    public function addKomponenMataPelajaran(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', '1')->get();
        $mata_pelajaran = MataPelajaran::with('jenis_mata_pelajaran')->isAktif()->get();
        $kelompok_sisipan = KelompokSisipan::with('sub_kelompok_sisipan')->get();
        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/add-komponen-mata-pelajaran', compact('auth_data', 'kelas', 'mata_pelajaran', 'kelompok_sisipan', 'id_kelas'));
    }

    public function copyKomponenMataPelajaran(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', '1')->get();
        $kelas_sisipan = KelasSisipan::get();
        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/copy-komponen-mata-pelajaran', compact('auth_data', 'kelas',  'id_kelas', 'kelas_sisipan'));
    }

    public function actionKomponenMataPelajaran(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $list_validator = [
            'id_kelas'              => 'required',
            'urutan'                => 'required',
            'id_mata_pelajaran'     => 'required',
            'id_kelompok_sisipan'   => 'required',
        ];

        $validator = Validator::make($request->all(), $list_validator);

        if ($validator->fails() && $mode != 'delete' && $mode != 'copy') {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'add') {
                $mata_pelajaran_sisipan = MataPelajaranSisipan::where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('urutan', $input->urutan)->where('id_kelompok_sisipan', $input->id_kelompok_sisipan)->first();

                if (empty($mata_pelajaran_sisipan)) {
                    $mata_pelajaran_sisipan = new MataPelajaranSisipan;
                    $mata_pelajaran_sisipan->id_mata_pelajaran_sisipan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    if (KelompokSisipan::find($input->id_kelompok_sisipan)) {
                        $mata_pelajaran_sisipan->id_kelompok_sisipan = $input->id_kelompok_sisipan;
                        $mata_pelajaran_sisipan->id_sub_kelompok_sisipan = null;
                    }

                    if (SubKelompokSisipan::find($input->id_kelompok_sisipan)) {
                        $mata_pelajaran_sisipan->id_kelompok_sisipan = null;
                        $mata_pelajaran_sisipan->id_sub_kelompok_sisipan = $input->id_kelompok_sisipan;
                    }

                    $mata_pelajaran_sisipan->id_mata_pelajaran = $input->id_mata_pelajaran;
                    $mata_pelajaran_sisipan->urutan = $input->urutan;
                    $mata_pelajaran_sisipan->jenis = '1';
                    $mata_pelajaran_sisipan->keterangan = null;
                    $mata_pelajaran_sisipan->save();
                }

                $kelas_sisipan = new KelasSisipan;
                $kelas_sisipan->id_kelas_sisipan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $kelas_sisipan->id_mata_pelajaran_sisipan = $mata_pelajaran_sisipan->id_mata_pelajaran_sisipan;
                $kelas_sisipan->id_kelas = $input->id_kelas;
                $kelas_sisipan->created_by = $input->auth_data->pengguna->id_pengguna;
                $kelas_sisipan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/komponen-mata-pelajaran/detail/' . $input->id_kelas,
                    'message' => 'Save Data List Form Succesfully'
                ];
            } elseif ($mode == 'delete') {
                $list_id_komponen = $input->list_id_komponen;

                KelasSisipan::whereIn('id_kelas_sisipan', $list_id_komponen)
                    ->update([
                        'deleted_by' => $input->auth_data->pengguna->id_pengguna,
                    ]);

                KelasSisipan::whereIn('id_kelas_sisipan', $list_id_komponen)->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Jenis succesfully'

                ];
            } elseif ($mode == 'copy') {
                $kelas_sisipans = KelasSisipan::where('id_kelas', $input->id_kelas)->get();
                foreach ($kelas_sisipans as $kelas_sisipan) {
                    $k = new KelasSisipan;
                    $k->id_kelas_sisipan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $k->id_kelas = $input->kelas;
                    $k->id_mata_pelajaran_sisipan = $kelas_sisipan->id_mata_pelajaran_sisipan;
                    $k->created_by =  $input->auth_data->pengguna->id_pengguna;
                    $k->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/komponen-mata-pelajaran/detail/' . $input->kelas,
                    'message' => 'Save Data List Form Succesfully'
                ];
            }
        }
    }
}
