<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KelasRapor;
use App\Models\KelompokMapelRapor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
// use App\Models\KelasSisipan;
// use App\Models\KelompokSisipan;
use App\Models\MataPelajaran;
use App\Models\MataPelajaranRapor;
use App\Models\SubKelompokMapelRapor;
// use App\Models\MataPelajaranSisipan;
// use App\Models\SubKelompokSisipan;
use Validator;

class KomponenMataPelajaranController extends Controller
{
    public function viewKomponenMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
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
        $auth_data = auth_data();
        $kelas = Kelas::where('is_aktif', '1')->get();
        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/detail-komponen-mata-pelajaran', compact('auth_data', 'kelas', 'id_kelas'));
    }

    public function datatablesKomponenMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        // $list_kelas_sisipan = KelasSisipan::with('mata_pelajaran_sisipan.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->where('id_kelas', $input->id_kelas)
        //     ->whereHas('mata_pelajaran_sisipan', function ($q) {
        //         $q->where('jenis', '1');
        //     });
        // $kelas_rapors = KelasRapor::with('mata_pelajaran_rapor.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
        //     $q->where('nm_rapor', 'sisipan');
        // })->get();

        $list_kelas_rapor = KelasRapor::with('mata_pelajaran_rapor.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->where('id_kelas', $input->id_kelas)
            ->whereHas('mata_pelajaran_rapor', function ($q) {
                $q->where('jenis', '1');
            })->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
                $q->where('nm_rapor', 'sisipan');
            });

        return Datatables::of($list_kelas_rapor)
            ->addColumn('nm_mata_pelajaran', function ($item) {
                if (isset($item->mata_pelajaran_rapor->mata_pelajaran)) {
                    return  $item->mata_pelajaran_rapor->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran . ' - ' . $item->mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                } else {
                    return 'Mapel DIhapus';
                }
            })
            ->addColumn('nm_kelompok_mapel_rapor', function ($item) {
                if ($item->mata_pelajaran_rapor->kelompok_mapel_rapor) {
                    return $item->mata_pelajaran_rapor->kelompok_mapel_rapor->nm_kelompok_mapel_rapor;
                } else if ($item->mata_pelajaran_rapor->sub_kelompok_mapel_rapor) {
                    return  $item->mata_pelajaran_rapor->sub_kelompok_mapel_rapor->kelompok_mapel_rapor->nm_kelompok_mapel_rapor . " - " . $item->mata_pelajaran_rapor->sub_kelompok_mapel_rapor->nm_sub_kelompok_mapel_rapor;
                } else {
                    return '-';
                }
            })->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kelas_rapor,

                );
                return $data;
            })
            ->make(true);
    }

    public function addKomponenMataPelajaran(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kelas = Kelas::where('is_aktif', '1')->get();
        $mata_pelajaran = MataPelajaran::with('jenis_mata_pelajaran')->isAktif()->get();
        // $kelompok_sisipan = KelompokSisipan::with('sub_kelompok_sisipan')->get();
        $kelompok_mapel_rapor = KelompokMapelRapor::where('nm_rapor', 'sisipan')->with('sub_kelompok_mapel_rapor')->get();
        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/add-komponen-mata-pelajaran', compact('auth_data', 'kelas', 'mata_pelajaran', 'kelompok_mapel_rapor', 'id_kelas'));
    }

    public function copyKomponenMataPelajaran(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kelas = Kelas::where('is_aktif', '1')->get();
        // $kelas_sisipan = KelasSisipan::get();
        $kelas_rapors = KelasRapor::with('mata_pelajaran_rapor.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'sisipan');
        })->get();

        return view('akademik/rapor-sisipan/komponen-mata-pelajaran/copy-komponen-mata-pelajaran', compact('auth_data', 'kelas',  'id_kelas', 'kelas_rapors'));
    }

    public function actionKomponenMataPelajaran(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $now = Carbon::now();
        $list_validator = [
            'id_kelas'              => 'required',
            'urutan'                => 'required',
            'id_mata_pelajaran'     => 'required',
            'id_kelompok_mapel_rapor'   => 'required',
        ];

        $validator = Validator::make($request->all(), $list_validator);

        if ($validator->fails() && $mode != 'delete' && $mode != 'copy') {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'add') {
                $mata_pelajaran_sisipan = MataPelajaranRapor::where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('urutan', $input->urutan)->where('id_kelompok_mapel_rapor', $input->id_kelompok_mapel_rapor)->first();

                if (empty($mata_pelajaran_sisipan)) {
                    $mata_pelajaran_sisipan = new MataPelajaranRapor;
                    $mata_pelajaran_sisipan->id_mata_pelajaran_rapor = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                    if (KelompokMapelRapor::find($input->id_kelompok_mapel_rapor)) {
                        $mata_pelajaran_sisipan->id_kelompok_mapel_rapor = $input->id_kelompok_mapel_rapor;
                        $mata_pelajaran_sisipan->id_sub_kelompok_mapel_rapor = null;
                    }

                    if (SubKelompokMapelRapor::find($input->id_kelompok_mapel_rapor)) {
                        $mata_pelajaran_sisipan->id_kelompok_mapel_rapor = null;
                        $mata_pelajaran_sisipan->id_sub_kelompok_mapel_rapor = $input->id_kelompok_mapel_rapor;
                    }

                    $mata_pelajaran_sisipan->id_mata_pelajaran = $input->id_mata_pelajaran;
                    $mata_pelajaran_sisipan->urutan = $input->urutan;
                    $mata_pelajaran_sisipan->jenis = '1';
                    $mata_pelajaran_sisipan->keterangan = null;
                    $mata_pelajaran_sisipan->save();
                }

                // $kelas_sisipan = new KelasRapor;
                // $kelas_sisipan->id_kelas_sisipan = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                // $kelas_sisipan->id_mata_pelajaran_sisipan = $mata_pelajaran_sisipan->id_mata_pelajaran_sisipan;
                // $kelas_sisipan->id_kelas = $input->id_kelas;
                // $kelas_sisipan->created_by = auth_data()->pengguna->id_pengguna;
                // $kelas_sisipan->save();

                $kelas_rapor = new KelasRapor;
                $kelas_rapor->id_kelas_rapor = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                $kelas_rapor->id_mata_pelajaran_rapor = $mata_pelajaran_sisipan->id_mata_pelajaran_rapor;
                $kelas_rapor->id_kelas = $input->id_kelas;
                $kelas_rapor->created_by = auth_data()->pengguna->id_pengguna;
                $kelas_rapor->save();


                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/komponen-mata-pelajaran/detail/' . $input->id_kelas,
                    'message' => 'Save Data List Form Succesfully'
                ];
            } elseif ($mode == 'delete') {
                if (!isset($input->list_id_komponen)) {
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Data Kosong'

                    ];
                }

                $list_id_komponen = $input->list_id_komponen;

                KelasRapor::whereIn('id_kelas_rapor', $list_id_komponen)
                    ->update([
                        'deleted_by' => auth_data()->pengguna->id_pengguna,
                    ]);

                KelasRapor::whereIn('id_kelas_rapor', $list_id_komponen)->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Jenis succesfully'

                ];
            } elseif ($mode == 'copy') {
                // $kelas_sisipans = KelasSisipan::where('id_kelas', $input->id_kelas)->get();
                $kelas_rapors = KelasRapor::where('id_kelas', $input->id_kelas)->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
                    $q->where('nm_rapor', 'sisipan');
                })->get();
                // foreach ($kelas_sisipans as $kelas_sisipan) {
                //     $k = new KelasSisipan;
                //     $k->id_kelas_sisipan = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                //     $k->id_kelas = $input->kelas;
                //     $k->id_mata_pelajaran_sisipan = $kelas_sisipan->id_mata_pelajaran_sisipan;
                //     $k->created_by =  auth_data()->pengguna->id_pengguna;
                //     $k->save();
                // }

                foreach ($kelas_rapors as $kelas_rapor) {
                    $k = new KelasRapor;
                    $k->id_kelas_rapor = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                    $k->id_kelas = $input->kelas;
                    $k->id_mata_pelajaran_rapor = $kelas_rapor->id_mata_pelajaran_rapor;
                    $k->created_by =  auth_data()->pengguna->id_pengguna;
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
