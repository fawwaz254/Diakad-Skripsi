<?php

namespace App\Http\Controllers\Keuangan\DataKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\BiayaSekolah as BiayaSekolah;
use App\Models\DetailBiaya as DetailBiaya;
use App\Models\KelompokBiaya;
use App\Models\Semester;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Jobs\CopyBiayaSekolah;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class BiayaSekolahController extends BaseController
{
    public function viewBiayaSekolah(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        return view('keuangan/data-keuangan/biaya-sekolah/view-biaya-sekolah', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelompok_biaya'));
    }

    public function addBiayaSekolah(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $id_biaya_sekolah = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('keuangan/data-keuangan/biaya-sekolah/add-biaya-sekolah', compact('auth_data', 'data_kelompok_biaya', 'data_semester', 'data_jalur', 'id_biaya_sekolah'));
    }

    public function editBiayaSekolah($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        $data_biaya_sekolah = LibDataKeuangan::fetchDataBiayaSekolah($auth_data, null, $id);

        return view('keuangan/data-keuangan/biaya-sekolah/edit-biaya-sekolah', compact('auth_data', 'data_kelompok_biaya', 'data_semester', 'data_jalur', 'data_biaya_sekolah'));
    }

    public function copyBiayaSekolah(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $now = (int) $semester->thn_akademik_semester + 1;

        $tahun_sebelum = (int) $semester->thn_akademik_semester - 2;

        $data_semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                            ->whereBetween('thn_akademik_semester', [$tahun_sebelum, $now])
                            ->orderBy('thn_akademik_semester', 'asc')
                            ->orderBy('nm_semester', 'asc')
                            ->get();

        return view('keuangan/data-keuangan/biaya-sekolah/copy-biaya-sekolah', compact('auth_data', 'data_semester'));
    }

    public function datatablesBiayaSekolah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $list_data = LibDataKeuangan::fetchDataBiayaSekolah($auth_data, null, null, true);

        if(!empty($input->tahun_akademik_semester)){
            $tahun      = $input->tahun_akademik_semester;

            $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();
            $list_data = $list_data->whereIn('biaya_sekolah.id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester]);
        }

        if(!empty($input->kelompok_biaya)){
            $list_data = $list_data->where('biaya_sekolah.id_kelompok_biaya', $input->kelompok_biaya);
        }

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('jalur', function ($item) {
                    if (! empty($item->nm_jalur)) {
                        return $item->nm_jalur;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('besar_biaya_sekolah', function ($item) {
                    return "Rp".number_format($item->besar_biaya_sekolah);
                })
                ->addColumn('validasi_biaya_sekolah', function ($item) {
                    if ($item->validasi_biaya_sekolah == 0) {
                        return "Belum";
                    } else {
                        return "Sudah";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_biaya_sekolah
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionBiayaSekolah(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya' => 'required',
            'id_semester' => 'required',
            //'id_jalur' => 'required',
            'besar_biaya_sekolah' => 'required',
            'validasi_biaya_sekolah' => 'required',
            'keterangan_biaya_sekolah' => 'required',
        ]);
        
        if ($validator->fails() && $mode != 'delete' && $mode != 'copy') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $biayaSekolah                               = new BiayaSekolah;
                $biayaSekolah->id_biaya_sekolah             = $id;
                $biayaSekolah->id_kelompok_biaya            = $input->id_kelompok_biaya;
                $biayaSekolah->id_semester                  = $input->id_semester;
                if (! empty($input->id_jalur)) {
                    $biayaSekolah->id_jalur                 = $input->id_jalur;
                }
                // $biayaSekolah->besar_biaya_sekolah          = $input->besar_biaya_sekolah;
                $biayaSekolah->besar_biaya_sekolah          = 0;
                $biayaSekolah->validasi_biaya_sekolah       = $input->validasi_biaya_sekolah;
                $biayaSekolah->keterangan_biaya_sekolah     = $input->keterangan_biaya_sekolah;
                $biayaSekolah->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $biayaSekolah->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-sekolah',
                    'message' => 'Save Biaya Sekolah successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $biayaSekolah                               = BiayaSekolah::find($id);
                $biayaSekolah->id_kelompok_biaya            = $input->id_kelompok_biaya;
                $biayaSekolah->id_semester                  = $input->id_semester;
                if (! empty($input->id_jalur)) {
                    $biayaSekolah->id_jalur                 = $input->id_jalur;
                }
                // $biayaSekolah->besar_biaya_sekolah          = $input->besar_biaya_sekolah;
                $biayaSekolah->besar_biaya_sekolah          = 0;
                $biayaSekolah->validasi_biaya_sekolah       = $input->validasi_biaya_sekolah;
                $biayaSekolah->keterangan_biaya_sekolah     = $input->keterangan_biaya_sekolah;
                $biayaSekolah->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $biayaSekolah->updated_at                   = $now;
                $biayaSekolah->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-keuangan/biaya-sekolah',
                    'message' => 'Update Biaya Sekolah successfully'
                ];
            } elseif ($mode == 'copy') {
                DB::beginTransaction();

                try {
                    $semester_paste = Semester::find($input->id_semester_paste);
                    $semester_copy = Semester::find($input->id_semester_copy);

                    // proses tabel biaya_sekolah
                    $biaya_sekolah_set = BiayaSekolah::where('id_semester', '=', $input->id_semester_copy)->get();

                    $batch_insert_biaya_sekolah = [];
                    $batch_insert_detail_biaya = [];
                    foreach ($biaya_sekolah_set as $biaya_sekolah) {
                        $id_biaya_sekolah           = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $id_kelompok_biaya          = $biaya_sekolah->id_kelompok_biaya;
                        $id_semester                = $input->id_semester_paste;
                        $id_jalur                   = $biaya_sekolah->id_jalur;
                        $besar_biaya_sekolah        = $biaya_sekolah->besar_biaya_sekolah;
                        $validasi_biaya_sekolah     = $biaya_sekolah->validasi_biaya_sekolah;
                        $keterangan_biaya_sekolah   = $biaya_sekolah->keterangan_biaya_sekolah;

                        $batch_insert_biaya_sekolah[] = array(
                            'id_biaya_sekolah'          => $id_biaya_sekolah,
                            'id_kelompok_biaya'         => $id_kelompok_biaya,
                            'id_semester'               => $id_semester,
                            'id_jalur'                  => $id_jalur,
                            'besar_biaya_sekolah'       => $besar_biaya_sekolah,
                            'validasi_biaya_sekolah'    => $validasi_biaya_sekolah,
                            'keterangan_biaya_sekolah'  => $keterangan_biaya_sekolah,
                            'created_by'                => $input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now
                        );

                        // proses tabel detail_biaya
                        $detail_biaya_set = DetailBiaya::where('id_biaya_sekolah', '=', $biaya_sekolah->id_biaya_sekolah)->get();

                        foreach ($detail_biaya_set as $detail_biaya) {
                            $id_detail_biaya            = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $id_biaya                   = $detail_biaya->id_biaya;
                            $id_kelompok_biaya_internal = $detail_biaya->id_kelompok_biaya_internal;
                            $validasi_biaya             = $detail_biaya->validasi_biaya;
                            $besar_biaya                = $detail_biaya->besar_biaya;
                            $keterangan_biaya           = $detail_biaya->keterangan_biaya;
                            $id_jenis_detail_biaya      = $detail_biaya->id_jenis_detail_biaya;
                            if ($semester_copy->nm_semester == $semester_paste->nm_semester) {
                                $id_bulan                   = $detail_biaya->id_bulan;
                            } else {
                                $id_bulan                   = $detail_biaya->id_bulan + 6;
                                if ($id_bulan > 12) {
                                    $id_bulan = $id_bulan - 12;
                                }
                            }

                            $batch_insert_detail_biaya[] = array(
                                'id_detail_biaya'               => $id_detail_biaya,
                                'id_biaya_sekolah'              => $id_biaya_sekolah,
                                'id_biaya'                      => $id_biaya,
                                'id_kelompok_biaya_internal'    => $id_kelompok_biaya_internal,
                                'validasi_biaya'                => $validasi_biaya,
                                'besar_biaya'                   => $besar_biaya,
                                'keterangan_biaya'              => $keterangan_biaya,
                                'id_jenis_detail_biaya'         => $id_jenis_detail_biaya,
                                'id_bulan'                      => $id_bulan,
                                'created_by'                    => $input->auth_data->pengguna->id_pengguna,
                                'created_at'                    => $now
                            );
                        }
                    }

                    CopyBiayaSekolah::dispatch($batch_insert_biaya_sekolah, $batch_insert_detail_biaya);

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-keuangan/biaya-sekolah',
                        'message' => 'Copy Biaya Sekolah successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 300, // GAGAL
                        'message' => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error. Error '.$e->getLine()
                    ];
                }
            } elseif ($mode == 'delete') {
                if ($detailBiaya = DetailBiaya::where('id_biaya_sekolah', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Biaya Sekolah'
                    ];
                } else {
                    // make object to find id
                    $biayaSekolah               = BiayaSekolah::find($id);
                    $biayaSekolah->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $biayaSekolah->save();

                    $biayaSekolah->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Biaya Sekolah successfully'
                    ];
                }
            }
        }
    }
}
