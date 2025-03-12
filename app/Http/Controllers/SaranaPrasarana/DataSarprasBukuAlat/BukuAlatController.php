<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasBukuAlat;

use App\Imports\DataImportExcel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JenisBukuAlat;
use App\Models\BukuAlat as BukuAlat;
use App\Models\Kelas as Kelas;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\Akademik\LibAkademik;
use App\Models\MataPelajaran;
use Auth;
use DB;
use Session;
use Validator;
use Excel;

class BukuAlatController extends BaseController
{

    public function viewBukuAlat(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('sarana-prasarana/data-sarpras-buku-alat/buku-alat/view-buku-alat', compact('auth_data'));
    }

    public function addBukuAlat(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        $data_jenis_buku_alat = LibDataSarpras::fetchDataJenisBukuAlat($auth_data);

        $data_tingkat_pendidikan = Kelas::select('tingkat')->where('is_aktif', 1)->distinct()->orderBy('tingkat', 'ASC')->get();

        $data_mata_pelajaran = LibAkademik::fetchDataMataPelajaran($auth_data);

        $id_buku_alat = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/data-sarpras-buku-alat/buku-alat/add-buku-alat', compact('auth_data', 'data_jenis_buku_alat', 'data_tingkat_pendidikan', 'data_mata_pelajaran', 'id_buku_alat'));
    }

    public function editBukuAlat($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_jenis_buku_alat = LibDataSarpras::fetchDataJenisBukuAlat($auth_data);

        $data_tingkat_pendidikan = Kelas::select('tingkat')->where('is_aktif', 1)->distinct()->orderBy('tingkat', 'ASC')->get();

        $data_mata_pelajaran = LibAkademik::fetchDataMataPelajaran($auth_data);

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data, $id);

        // convert format date
        $tgl_pembelian = strftime("%A, %d %B %Y", strtotime($data_buku_alat->tgl_pembelian));

        return view('sarana-prasarana/data-sarpras-buku-alat/buku-alat/edit-buku-alat', compact('auth_data', 'data_jenis_buku_alat', 'data_tingkat_pendidikan', 'data_mata_pelajaran', 'data_buku_alat', 'tgl_pembelian'));
    }

    public function datatablesBukuAlat(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataSarpras::fetchDataBukuAlat($auth_data);

        return Datatables::of($list_data)
            ->addColumn('nm_jenis_buku_alat', function ($item) {
                return $item->nm_jenis_buku_alat . " - " . $item->kode_jenis_buku_alat;
            })
            ->addColumn('nm_mata_pelajaran', function ($item) {
                return $item->nm_mata_pelajaran . " - " . $item->nm_jurusan;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_buku_alat
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionBukuAlat(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jenis_buku_alat'            => 'required',
            'nm_buku_alat'                  => 'required',
            /*'tingkat_pendidikan_buku_alat'  => 'required',
            'id_mata_pelajaran'             => 'required',*/
            'kode_buku_alat'                => 'required',
            'tgl_pembelian'                 => 'required',
            'jumlah_buku_alat'              => 'required',
            'jumlah_kondisi_baik'           => 'required',
            'jumlah_kondisi_rusak'          => 'required',
            'keterangan_buku_alat'          => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $bukuAlat                         = new BukuAlat;
                $bukuAlat->id_buku_alat           = $id;
                $bukuAlat->id_jenis_buku_alat     = $input->id_jenis_buku_alat;
                $bukuAlat->nm_buku_alat           = $input->nm_buku_alat;
                if (!empty($input->tingkat_pendidikan_buku_alat)) {
                    $bukuAlat->tingkat_pendidikan_buku_alat    = $input->tingkat_pendidikan_buku_alat;
                }
                if (!empty($input->id_mata_pelajaran)) {
                    $bukuAlat->id_mata_pelajaran               = $input->id_mata_pelajaran;
                }
                $bukuAlat->kode_buku_alat         = $input->kode_buku_alat;
                // convert format date
                $bukuAlat->tgl_pembelian          = date_format(date_create($input->tgl_pembelian), "Y-m-d H:i:s");
                $bukuAlat->jumlah_buku_alat       = $input->jumlah_buku_alat;
                $bukuAlat->jumlah_kondisi_baik    = $input->jumlah_kondisi_baik;
                $bukuAlat->jumlah_kondisi_rusak   = $input->jumlah_kondisi_rusak;
                $bukuAlat->keterangan_buku_alat   = $input->keterangan_buku_alat;
                $bukuAlat->created_by             = auth_data()->pengguna->id_pengguna;
                $bukuAlat->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-buku/buku-alat',
                    'message' => 'Save Buku/Alat Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $bukuAlat                         = BukuAlat::find($id);
                $bukuAlat->id_jenis_buku_alat     = $input->id_jenis_buku_alat;
                $bukuAlat->nm_buku_alat           = $input->nm_buku_alat;
                if (!empty($input->tingkat_pendidikan_buku_alat)) {
                    $bukuAlat->tingkat_pendidikan_buku_alat    = $input->tingkat_pendidikan_buku_alat;
                }
                if (!empty($input->id_mata_pelajaran)) {
                    $bukuAlat->id_mata_pelajaran               = $input->id_mata_pelajaran;
                }
                $bukuAlat->kode_buku_alat         = $input->kode_buku_alat;
                // convert format date
                $bukuAlat->tgl_pembelian          = date_format(date_create($input->tgl_pembelian), "Y-m-d H:i:s");
                $bukuAlat->jumlah_buku_alat       = $input->jumlah_buku_alat;
                $bukuAlat->jumlah_kondisi_baik    = $input->jumlah_kondisi_baik;
                $bukuAlat->jumlah_kondisi_rusak   = $input->jumlah_kondisi_rusak;
                $bukuAlat->keterangan_buku_alat   = $input->keterangan_buku_alat;
                $bukuAlat->updated_by             = auth_data()->pengguna->id_pengguna;
                $bukuAlat->updated_at             = $now;
                $bukuAlat->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sarpras-buku-alat/buku-alat',
                    'message' => 'Update Buku/Alat Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $bukuAlat               = BukuAlat::find($id);
                $bukuAlat->deleted_by   = auth_data()->pengguna->id_pengguna;
                $bukuAlat->save();

                $bukuAlat->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Buku/Alat Successfully'
                ];
            }
        }
    }

    public function importExcel(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('sarana-prasarana/data-sarpras-buku-alat/buku-alat/import-excel', compact('auth_data'));
    }

    public function importExcelAction(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'file-excel' => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($request->hasFile('file-excel')) {

                $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
                $data = $data[0];

                if (count($data)) {

                    DB::beginTransaction();

                    try {

                        foreach ($data as $key => $value) {
                            $value = (object) $value;

                            if (empty($value->jenis_buku_atau_alat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data jenis buku/alat gagal, ada nama jenis buku / alat yang kosong'
                                ];
                            }

                            $check_jenis_buku_alat = JenisBukuAlat::where('nm_jenis_buku_alat', $value->jenis_buku_atau_alat)->first();

                            if (!$check_jenis_buku_alat) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada jenis buku / alat yang anda masukkan tidak ada di dalam master jenis buku/alat'
                                ];
                            }

                            if (empty($value->nama_buku_atau_alat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada nama buku / alat yang kosong'
                                ];
                            }

                            //  if(empty($value->jenis) && ($value->jenis== 0)){
                            //     return [
                            //         'status'    => 203, // GAGAL
                            //         'message'   => 'Upload data buku/alat gagal, ada jenis yang kosong'
                            //     ];
                            // }

                            // if(ucwords($value->jenis)!='Alat' || ucwords($value->jenis)!='Buku'){
                            //     return [
                            //         'status'    => 203, // GAGAL
                            //         'message'   => 'Upload data buku/alat gagal, pilihat jenis hanyalah Alat / Buku'
                            //     ];
                            // }
                            if (empty($value->tingkat_pendidikan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada tingkat pendidikan yang kosong'
                                ];
                            }

                            if (empty($value->mata_pelajaran)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada mata pelajaran yang kosong'
                                ];
                            }
                            $check_mata_pelajaran = MataPelajaran::where('nm_mata_pelajaran', $value->mata_pelajaran)->first();

                            if (!$check_mata_pelajaran) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada jenis mata pelajaran yang tidak ditemukan dalam data master mata pelajaran di role Akademik'
                                ];
                            }

                            if (empty($value->kode_buku_atau_alat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada kode buku / alat yang kosong'
                                ];
                            }

                            if (empty($value->tanggal_pembelian)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada tanggal pembelian yang kosong'
                                ];
                            }

                            if (empty($value->jumlah_buku_atau_alat)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data buku/alat gagal, ada jumlah buku / alat yang kosong'
                                ];
                            }

                            if (empty($value->kondisi_baik)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada kondisi_baik yang kosong'
                                ];
                            }

                            if (empty($value->kondisi_rusak) && ($value->kondisi_rusak == 0)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada kondisi rusak yang kosong'
                                ];
                            }

                            if (empty($value->keterangan)) {
                                return [
                                    'status'    => 203, // GAGAL
                                    'message'   => 'Upload data inventaris gagal, ada keterangan yang kosong'
                                ];
                            }

                            $data                                 = new BukuAlat;
                            $data->id_buku_alat                   = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $data->id_jenis_buku_alat             = $check_jenis_buku_alat->id_jenis_buku_alat;
                            $data->nm_buku_alat                   = $value->nama_buku_atau_alat;
                            $data->kode_buku_alat                 = $value->kode_buku_atau_alat;
                            $data->tingkat_pendidikan_buku_alat   = $value->tingkat_pendidikan;
                            $data->id_mata_pelajaran              = $value->mata_pelajaran;
                            $data->tgl_pembelian                  = date('Y-m-d', strtotime($value->tanggal_pembelian));
                            $data->jumlah_buku_alat               = $value->jumlah_buku_atau_alat;
                            $data->jumlah_kondisi_baik            = $value->kondisi_baik;
                            $data->jumlah_kondisi_rusak           = $value->kondisi_rusak;
                            $data->keterangan_buku_alat           = $value->keterangan;
                            $data->created_by                     = auth_data()->pengguna->id_pengguna;
                            $data->save();
                        }

                        DB::commit();

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'data-sarpras-buku-alat/buku-alat',
                            'message' => 'Import Pemilik Sarpras Successfully'
                        ];
                    } catch (\Exception $e) {

                        DB::rollback();

                        return [
                            'status'    => 203, // GAGAL
                            'message'       => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
                        ];
                    }
                } else {

                    return [
                        'status'    => 300, // FAILED
                        'message'   => "File excel anda kosong"
                    ];
                }
            } else {
                return [
                    'status'    => 300, // FAILED
                    'message'   => "File Excel tidak ditemukan"
                ];
            }
        }
    }
}
