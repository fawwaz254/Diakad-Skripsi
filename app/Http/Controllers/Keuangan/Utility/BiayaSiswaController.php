<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Siswa as Siswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Models\DetailBiaya;
use App\Models\PemasukanBiaya;
use App\Models\PembayaranBiaya;
use App\Models\TagihanBiaya;
use Auth;
use DB;
use Session;
use Validator;

class BiayaSiswaController extends BaseController
{
    public function viewBiayaSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/utility/biaya-siswa/view-biaya-siswa', compact('auth_data'));
    }

    public function viewBiayaSiswaByKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        return view('keuangan/utility/biaya-siswa-by-kelas/view-biaya-siswa-by-kelas', compact('auth_data', 'data_kelas', 'data_kelompok_biaya'));
    }

    public function setBiayaSiswa($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        // ambil data kelompok biaya
        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_biaya_siswa = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, null, $id);

        return view('keuangan/utility/biaya-siswa/set-biaya-siswa', compact('auth_data', 'data_kelompok_biaya', 'data_biaya_siswa'));
    }

    public function editBiayaSiswa($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data kelompok biaya
        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_biaya_siswa = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, null, $id);

        return view('keuangan/utility/biaya-siswa/edit-biaya-siswa', compact('auth_data', 'data_kelompok_biaya', 'data_biaya_siswa'));
    }

    public function datatablesBiayaSiswaBelum(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (!empty($input->id_kelas)) {
            if ($input->id_kelas == 'notset') {
                $list_data = array();
            } else {
                $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 0, null, $input->id_kelas, "1");
            }
        } else {
            $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 0, null, null, "1");
        }

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->editColumn('jenis_kelamin', function ($item) {
                if ($item->jenis_kelamin == 1) {
                    return 'Laki-Laki';
                } else if ($item->jenis_kelamin == 2) {
                    return 'Perempuan';
                } else {
                    return 'Belum diset';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesBiayaSiswaSudah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (!empty($input->id_kelas)) {
            if ($input->id_kelas == 'notset') {
                $list_data = array();
            } else {
                $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 1, null, $input->id_kelas, "1");
            }
        } else {
            $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 1, null, null, "1");
        }

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->editColumn('jenis_kelamin', function ($item) {
                if ($item->jenis_kelamin == 1) {
                    return 'Laki-Laki';
                } else if ($item->jenis_kelamin == 2) {
                    return 'Perempuan';
                } else {
                    return 'Belum diset';
                }
            })
            ->addColumn('kelompok_biaya', function ($item) {
                if ($item->status_kelompok_biaya == 1) {
                    return $item->nm_kelompok_biaya;
                } else {
                    return $item->nm_kelompok_biaya;
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionBatchBiayaSiswa(Request $request, $mode)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'set') {
                if (!isset($input->id_siswa)) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Pilih Siswa Terlebih Dahulu'
                    ];
                }

                $id_siswa_collection = collect($input->id_siswa);

                foreach ($id_siswa_collection->chunk(25) as $chunk_id_siswa) {
                    foreach ($chunk_id_siswa as $id_siswa) {
                        $siswa = Siswa::find($id_siswa);
                        $siswa->id_kelompok_biaya = $input->id_kelompok_biaya;
                        $siswa->save();
                    }
                }

                return [
                    'status' => 200, // SUCCESS AND LOAD CONTENT
                    'message' => 'Save Biaya Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {

                if (isset($input->id_siswa)) {
                    $id_siswa_collection = collect($input->id_siswa);

                    foreach ($id_siswa_collection->chunk(25) as $chunk_id_siswa) {
                        foreach ($chunk_id_siswa as $id_siswa) {
                            $siswa = Siswa::find($id_siswa);
                            $siswa->id_kelompok_biaya = $input->id_kelompok_biaya;
                            $siswa->save();
                        }
                    }

                    return [
                        'status' => 200, // SUCCESS AND LOAD CONTENT
                        'message' => 'Update Biaya Siswa Successfully'
                    ];
                }
                return [
                    'status' => 300, // FAILED
                    'message' => 'Pilih Siswa Terlebih Dahulu'
                ];
            } elseif ($mode == 'delete') {
                $id_siswa_collection = collect($input->id_siswa);

                foreach ($id_siswa_collection->chunk(25) as $chunk_id_siswa) {
                    foreach ($chunk_id_siswa as $id_siswa) {
                        $siswa = Siswa::find($id_siswa);
                        $siswa->id_kelompok_biaya = null;
                        $siswa->save();
                    }
                }

                return [
                    'status' => 200, // SUCCESS AND LOAD CONTENT
                    'message' => 'Delete Biaya Siswa Successfully'
                ];
            }
        }
    }

    // Action POST
    public function actionBiayaSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'set') {
                // make object to find id
                $siswa = Siswa::find($id);
                $siswa->id_kelompok_biaya = $input->id_kelompok_biaya;
                $siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'utility/biaya-siswa',
                    'message' => 'Save Biaya Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $siswa = Siswa::find($id);
                isset($input->ganti_kelompok_biaya);
                if (isset($input->ganti_kelompok_biaya) && $input->ganti_kelompok_biaya == '1') {
                    $auth_data = $input->auth_data;
                    $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

                    //validasi
                    $validasi_pembayaran = TagihanBiaya::where('is_tagih', '0')->where('id_siswa', $siswa->id_siswa)->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($siswa, $semester_aktif) {
                        $query->where('id_kelompok_biaya', '=', $siswa->id_kelompok_biaya)->whereHas('semester', function ($query) use ($semester_aktif) {
                            $query->where('thn_akademik_semester', '=', $semester_aktif->thn_akademik_semester);
                        });
                    })->first();

                    if ($validasi_pembayaran) {
                        return [
                            'status' => 203, // SUCCESS AND LOAD TABLE
                            'message' => 'Ada Pembayaran, Hapus terlebih dahulu pembayarannya'
                        ];
                    }

                    //hapus tagihan lama
                    $tagihan_biaya_lama = TagihanBiaya::where('id_siswa', $siswa->id_siswa)->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($siswa, $semester_aktif) {
                        $query->where('id_kelompok_biaya', '=', $siswa->id_kelompok_biaya)->whereHas('semester', function ($query) use ($semester_aktif) {
                            $query->where('thn_akademik_semester', '=', $semester_aktif->thn_akademik_semester);
                        });
                    })->get();

                    $pembayaran_biaya = PembayaranBiaya::whereIn('id_tagihan_biaya', $tagihan_biaya_lama->pluck('id_tagihan_biaya'))->get();
                    foreach ($pembayaran_biaya as $p) {
                        $p->deleted_by = 'Update Kelompok Biaya';
                        $p->save();
                        $p->delete();
                    }

                    foreach ($tagihan_biaya_lama as $t) {
                        $t->forceDelete();
                    }

                    //generate tagihan baru
                    $detail_biaya_set = DetailBiaya::withTrashed()
                        ->select('detail_biaya.id_detail_biaya', 'detail_biaya.besar_biaya', 'detail_biaya.keterangan_biaya', 'detail_biaya.deleted_at')
                        ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                        ->join('semester', 'semester.id_semester', '=', 'biaya_sekolah.id_semester')
                        ->where('biaya_sekolah.id_kelompok_biaya', '=', $input->id_kelompok_biaya)
                        ->where('semester.thn_akademik_semester', '=', $semester_aktif->thn_akademik_semester)
                        ->get();

                    foreach ($detail_biaya_set as $detail_biaya) {
                        //validasi
                        $tagihan_set = TagihanBiaya::withTrashed()->select('id_tagihan_biaya')
                            ->where('id_siswa', '=', $siswa->id_siswa)
                            ->where('id_detail_biaya', '=', $detail_biaya->id_detail_biaya)
                            ->first();

                        if ($tagihan_set) {
                            continue;
                        }

                        if (empty($detail_biaya->deleted_at)) {
                            $tagihanBiaya = new TagihanBiaya;
                            $tagihanBiaya->id_tagihan_biaya = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $tagihanBiaya->id_siswa = $siswa->id_siswa;
                            $tagihanBiaya->id_kelas = $siswa->id_kelas;
                            $tagihanBiaya->id_detail_biaya = $detail_biaya->id_detail_biaya;
                            $tagihanBiaya->besar_biaya = $detail_biaya->besar_biaya;
                            $tagihanBiaya->denda_biaya = 0;
                            $tagihanBiaya->is_tagih = 1;
                            $tagihanBiaya->keterangan = $detail_biaya->keterangan_biaya;
                            $tagihanBiaya->created_by = $input->auth_data->pengguna->id_pengguna;
                            $tagihanBiaya->save();
                        }
                    }
                }


                $siswa->id_kelompok_biaya = $input->id_kelompok_biaya;
                $siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'utility/biaya-siswa',
                    'message' => 'Update Biaya Siswa Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $siswa = Siswa::find($id);
                $siswa->id_kelompok_biaya = null;
                $siswa->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Biaya Siswa Successfully'
                ];
            }
        }
    }
}
