<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\DetailBiaya as DetailBiaya;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Keuangan\LibDataKeuangan;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TagihanSiswaController extends BaseController
{
    public function viewTagihanSiswa(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $data_thn_masuk_siswa = Siswa::select('siswa.thn_masuk_siswa')
        //     ->distinct()
        //     ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
        //     ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        //     ->where('status_pengguna.aktif_status_pengguna', '=', 1)
        //     ->orderBy('thn_masuk_siswa', 'ASC')
        //     ->get();

        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'ASC')->orderBy('nm_kelas', 'ASC')->get();

        $data_semester = LibDataAkademik::fetchDataSemester($auth_data);
        $data_semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        // dd($data_semester);

        return view('keuangan/utility/tagihan-siswa/view-tagihan-siswa', compact('auth_data', 'kelas', 'data_semester', 'data_kelompok_biaya', 'data_jalur', 'data_semester_aktif'));
    }

    public function actionViewTagihanSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas'   => 'required',
            'thn_akademik_semester'       => 'required',
            /*'id_kelompok_biaya' => 'required',
            'id_jalur'          => 'required',*/
            'is_insert_replace' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'utility/tagihan-siswa/view-detail-tagihan-siswa/' . $input->id_kelas . '/' . $input->thn_akademik_semester . '/' . $input->id_kelompok_biaya . '/' . $input->id_jalur . '/' . $input->is_insert_replace
            ];
        }
    }

    public function viewDetailTagihanSiswa(Request $request, $id_kelas, $thn_akademik_semester, $id_kelompok_biaya, $id_jalur, $is_insert_replace)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $data_thn_masuk_siswa = Siswa::select('siswa.thn_masuk_siswa')
        //     ->distinct()
        //     ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
        //     ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
        //     ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        //     ->where('status_pengguna.aktif_status_pengguna', '=', 1)
        //     ->orderBy('thn_masuk_siswa', 'ASC')
        //     ->get();

        $kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat', 'ASC')->orderBy('nm_kelas', 'ASC')->get();

        $data_semester = LibDataAkademik::fetchDataSemester($auth_data);

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);
        $data_semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

        return view('keuangan/utility/tagihan-siswa/view-detail-tagihan-siswa', compact('auth_data', 'id_kelas', 'thn_akademik_semester', 'id_kelompok_biaya', 'id_jalur', 'is_insert_replace', 'kelas', 'data_semester', 'data_kelompok_biaya', 'data_jalur', 'data_semester_aktif'));
    }

    public function datatablesTagihanSiswa(Request $request, $id_kelas, $thn_akademik_semester, $id_kelompok_biaya, $id_jalur, $is_insert_replace)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($thn_akademik_semester);
        $siswa = LibDataKeuangan::fetchDataSiswaTagihan($auth_data, $id_kelas, $thn_akademik_semester, $id_kelompok_biaya, $id_jalur, "1");

        $tagihanQuery = TagihanBiaya::when($id_kelas != '0', function ($q) use ($id_kelas) {
            $q->where('id_kelas', $id_kelas);
        });

        if ($id_kelompok_biaya != 0) {
            $tagihanQuery->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($id_kelompok_biaya, $thn_akademik_semester) {
                $query->where('id_kelompok_biaya', '=', $id_kelompok_biaya)->where('semester.thn_akademik_semester', '=', $thn_akademik_semester);
            });
        } else {
            $tagihanQuery->whereHas('detail_biaya.biaya_sekolah.semester', function ($query) use ($thn_akademik_semester) {
                $query->where('thn_akademik_semester', '=', $thn_akademik_semester);
            });
        }

        $tagihan = $tagihanQuery->get();

        return Datatables::of($siswa)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->addColumn('kelompok_biaya', function ($item) {
                if ($item->status_kelompok_biaya == 1) {
                    return $item->nm_kelompok_biaya . " (Reguler)";
                } elseif ($item->status_kelompok_biaya == 2) {
                    return $item->nm_kelompok_biaya . " (Khusus)";
                } else {
                    return "Belum Di Set";
                }
            })
            ->addColumn('jumlah_tagihan', function ($item) use ($tagihan) {
                return $tagihan->where('id_siswa', $item->id_siswa)->count();
            })
            ->make(true);
    }


    // Action POST
    public function actionTagihanSiswa(Request $request, $mode)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required',
            'thn_akademik_semester' => 'required',
            'is_insert_replace' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            // dd($input->thn_akademik_semester);
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    foreach ($input->id_siswa as $id_siswa) {
                        $kelompok_biaya = Siswa::select('id_kelompok_biaya')->where('id_siswa', '=', $id_siswa)->first();

                        $detail_biaya_set = DetailBiaya::withTrashed()
                            ->select('detail_biaya.id_detail_biaya', 'detail_biaya.besar_biaya', 'detail_biaya.keterangan_biaya', 'detail_biaya.deleted_at')
                            ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                            ->join('semester', 'semester.id_semester', '=', 'biaya_sekolah.id_semester')
                            ->where('biaya_sekolah.id_kelompok_biaya', '=', $kelompok_biaya->id_kelompok_biaya)
                            ->where('semester.thn_akademik_semester', '=', $input->thn_akademik_semester)
                            ->get();

                        // dd($detail_biaya_set);

                        // detail biaya ambil sekalian yg with_trashed, kalau detail tersebut adalah trashed, maka tagihan juga di trashed
                        // hanya ada update dan insert
                        foreach ($detail_biaya_set as $detail_biaya) {
                            $tagihan_set = TagihanBiaya::withTrashed()->select('id_tagihan_biaya')
                                ->where('id_siswa', '=', $id_siswa)
                                ->where('id_detail_biaya', '=', $detail_biaya->id_detail_biaya)
                                ->first();

                            // dd($tagihan_set);
                            // dd($input);
                            if ($input->is_insert_replace == "3") { // UPDATE
                                $pembayaran = PembayaranBiaya::where('id_tagihan_biaya', $tagihan_set->id_tagihan_biaya)->first();

                                if (!empty($tagihan_set)) { // kalau tagihan ditemukan maka update(bisa edit/hapus)
                                    if (empty($pembayaran)) { // jika TIDAK ADA pembayaran
                                        $tagihan_set->keterangan        = $detail_biaya->keterangan_biaya;
                                        $tagihan_set->besar_biaya       = $detail_biaya->besar_biaya;
                                        $tagihan_set->updated_by        = $input->auth_data->pengguna->id_pengguna;
                                        if (!empty($detail_biaya->deleted_at)) {
                                            $tagihan_set->deleted_at        = $now;
                                            $tagihan_set->deleted_by        = $input->auth_data->pengguna->id_pengguna;
                                        }
                                        $tagihan_set->save();
                                    } else { // jika ADA pembayaran hanya update keterangan
                                        $tagihan_set->keterangan    = $detail_biaya->keterangan_biaya;
                                        $tagihan_set->updated_by    = $input->auth_data->pengguna->id_pengguna;
                                        $tagihan_set->save();
                                        // if(!empty($detail_biaya->deleted_at)){
                                        //     $pembayaran->delete();
                                        // }
                                    }
                                } else { // kalau tagihan dari detail biaya tidak ditemukan maka tambah baru
                                    $siswa = Siswa::find($id_siswa);

                                    $tagihanBiaya                       = new TagihanBiaya;
                                    $tagihanBiaya->id_tagihan_biaya     = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                    $tagihanBiaya->id_siswa             = $id_siswa;
                                    $tagihanBiaya->id_kelas             = $siswa->id_kelas;
                                    $tagihanBiaya->id_detail_biaya      = $detail_biaya->id_detail_biaya;
                                    $tagihanBiaya->besar_biaya          = $detail_biaya->besar_biaya;
                                    $tagihanBiaya->denda_biaya          = 0;
                                    $tagihanBiaya->is_tagih             = 1;
                                    $tagihanBiaya->keterangan           = $detail_biaya->keterangan_biaya;
                                    $tagihanBiaya->created_by           = $input->auth_data->pengguna->id_pengguna;
                                    $tagihanBiaya->save();
                                }
                            } elseif ($input->is_insert_replace == "4") { // Sync
                                $pembayaran = PembayaranBiaya::where('id_tagihan_biaya', $tagihan_set->id_tagihan_biaya)->first();

                                if (!empty($tagihan_set)) { // kalau tagihan ditemukan maka update
                                    // if (empty($pembayaran)) { // jika TIDAK ADA pembayaran
                                    $tagihan_set->keterangan        = $detail_biaya->keterangan_biaya;
                                    $tagihan_set->besar_biaya       = $detail_biaya->besar_biaya;
                                    $tagihan_set->updated_by        = $input->auth_data->pengguna->id_pengguna;
                                    if (!empty($detail_biaya->deleted_at)) { //jika detail biaya telah dihapus
                                        $tagihan_set->deleted_at        = $now;
                                        $tagihan_set->deleted_by        = $input->auth_data->pengguna->id_pengguna;
                                        if (!empty($pembayaran)) { // kalau pembayaran ditemukan maka update
                                            $pembayaran->deleted_at = $now;
                                            $pembayaran->deleted_by = $input->auth_data->pengguna->id_pengguna;
                                        }
                                    }

                                    if ($tagihan_set->is_tagih == '0') {
                                        $tagihan_set->besar_pembayaran = $detail_biaya->besar_biaya;
                                    }

                                    if (!empty($pembayaran)) { // kalau pembayaran ditemukan maka update
                                        $pembayaran->besar_pembayaran = $detail_biaya->besar_biaya;
                                        $pembayaran->save();
                                    }

                                    $tagihan_set->save();
                                } else { // kalau tagihan dari detail biaya tidak ditemukan maka tambah baru
                                    $siswa = Siswa::find($id_siswa);

                                    $tagihanBiaya                       = new TagihanBiaya;
                                    $tagihanBiaya->id_tagihan_biaya     = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                    $tagihanBiaya->id_siswa             = $id_siswa;
                                    $tagihanBiaya->id_kelas             = $siswa->id_kelas;
                                    $tagihanBiaya->id_detail_biaya      = $detail_biaya->id_detail_biaya;
                                    $tagihanBiaya->besar_biaya          = $detail_biaya->besar_biaya;
                                    $tagihanBiaya->denda_biaya          = 0;
                                    $tagihanBiaya->is_tagih             = 1;
                                    $tagihanBiaya->keterangan           = $detail_biaya->keterangan_biaya;
                                    $tagihanBiaya->created_by           = $input->auth_data->pengguna->id_pengguna;
                                    $tagihanBiaya->save();
                                }
                            } else {
                                if ($input->is_insert_replace == "1") { // INSERT
                                    if ($tagihan_set) {
                                        continue;
                                    }
                                }
                                // delete tagihan lama
                                elseif ($input->is_insert_replace == "2") { // REPLACE

                                    $pembayaranBiaya            = PembayaranBiaya::where('id_tagihan_biaya', $tagihan_set->id_tagihan_biaya)->first();

                                    if ($pembayaranBiaya) {
                                        return [
                                            'status' => 203, // GAGAL
                                            'message' => 'Generate Tagihan Siswa Gagal, Tagihan Pernah Dibayarkan!'
                                        ];
                                    } else {
                                        $tagihanBiaya               = TagihanBiaya::find($tagihan_set->id_tagihan_biaya);
                                        $tagihanBiaya->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                                        $tagihanBiaya->save();

                                        $tagihanBiaya->delete();
                                    }
                                }
                                if (empty($detail_biaya->deleted_at)) {
                                    $siswa = Siswa::find($id_siswa);

                                    $tagihanBiaya                       = new TagihanBiaya;
                                    $tagihanBiaya->id_tagihan_biaya     = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                    $tagihanBiaya->id_siswa             = $id_siswa;
                                    $tagihanBiaya->id_kelas             = $siswa->id_kelas;
                                    $tagihanBiaya->id_detail_biaya      = $detail_biaya->id_detail_biaya;
                                    $tagihanBiaya->besar_biaya          = $detail_biaya->besar_biaya;
                                    $tagihanBiaya->denda_biaya          = 0;
                                    $tagihanBiaya->is_tagih             = 1;
                                    $tagihanBiaya->keterangan           = $detail_biaya->keterangan_biaya;
                                    $tagihanBiaya->created_by           = $input->auth_data->pengguna->id_pengguna;
                                    $tagihanBiaya->save();
                                }
                            }
                        }
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'utility/tagihan-siswa/view-detail-tagihan-siswa/' . $input->id_kelas . '/' . $input->thn_akademik_semester . '/' . $input->id_kelompok_biaya . '/' . $input->id_jalur . '/' . $input->is_insert_replace,
                        'message' => $input->is_insert_replace == '3' ? 'Update Tagihan Siswa Successfully' : 'Generate Tagihan Siswa Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Generate Tagihan Siswa Gagal! ' . $e->getMessage()
                    ];
                }
            }
        }
    }
}
