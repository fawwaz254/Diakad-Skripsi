<?php

namespace App\Http\Controllers\SaranaPrasarana\PerawatanSarpras;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use App\Models\UnitKerja as UnitKerja;
use App\Models\RpbSarpras as RpbSarpras;
use App\Models\RpbSarprasSupplier as RpbSarprasSupplier;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class PengadaanSarprasController extends BaseController
{
    public function viewPengadaanSarpras(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/perawatan-sarpras/pengadaan-sarpras/view-pengadaan-sarpras', compact('auth_data'));
    }

    public function datatablesPengadaanSarprasTinggi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataPengadaanSarpras($auth_data, 3, null, 1);

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('harga_satuan_rpb_sarpras', function ($item) {
                    return "Rp".number_format($item->harga_satuan_rpb_sarpras);
                })
                ->addColumn('tgl_rpb_sarpras', function ($item) {
                    return strftime("%d %B %Y", strtotime($item->tgl_rpb_sarpras));
                })
                ->addColumn('nm_kepala_unit', function ($item) {
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rpb_sarpras' => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_sarpras', function ($item) {
                    $data = array(
                        'nm_kepala_sarpras'   => $item->nm_kepala_sarpras,
                        'id_rpb_sarpras'      => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_sarpras_approve', function ($item) {
                    $data = array(
                        'nm_kepala_sarpras_approve'     => $item->nm_kepala_sarpras_approve,
                        'id_rpb_sarpras'                => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('supplier', function ($item) {
                    $apv_supplier = "Belum Approve";

                    if($item->apv_supplier > 0) {
                        $apv_supplier = "Approve";
                    }

                    $data = array(
                        'id'            => $item->id_rpb_sarpras,
                        'apv_supplier'  => $apv_supplier
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesPengadaanSarprasSedang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataPengadaanSarpras($auth_data, 2, null, 1);

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('harga_satuan_rpb_sarpras', function ($item) {
                    return "Rp".number_format($item->harga_satuan_rpb_sarpras);
                })
                ->addColumn('tgl_rpb_sarpras', function ($item) {
                    return strftime("%d %B %Y", strtotime($item->tgl_rpb_sarpras));
                })
                ->addColumn('nm_kepala_unit', function ($item) {
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rpb_sarpras' => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_sarpras', function ($item) {
                    $data = array(
                        'nm_kepala_sarpras'   => $item->nm_kepala_sarpras,
                        'id_rpb_sarpras'      => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_sarpras_approve', function ($item) {
                    $data = array(
                        'nm_kepala_sarpras_approve'     => $item->nm_kepala_sarpras_approve,
                        'id_rpb_sarpras'                => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('supplier', function ($item) {
                    $apv_supplier = "Belum Approve";

                    if($item->apv_supplier > 0) {
                        $apv_supplier = "Approve";
                    }

                    $data = array(
                        'id'            => $item->id_rpb_sarpras,
                        'apv_supplier'  => $apv_supplier
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesPengadaanSarprasRendah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataPengadaanSarpras($auth_data, 1, null, 1);

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('harga_satuan_rpb_sarpras', function ($item) {
                    return "Rp".number_format($item->harga_satuan_rpb_sarpras);
                })
                ->addColumn('tgl_rpb_sarpras', function ($item) {
                    return strftime("%d %B %Y", strtotime($item->tgl_rpb_sarpras));
                })
                ->addColumn('nm_kepala_unit', function ($item) {
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rpb_sarpras' => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_sarpras', function ($item) {
                    $data = array(
                        'nm_kepala_sarpras'   => $item->nm_kepala_sarpras,
                        'id_rpb_sarpras'      => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_sarpras_approve', function ($item) {
                    $data = array(
                        'nm_kepala_sarpras_approve'     => $item->nm_kepala_sarpras_approve,
                        'id_rpb_sarpras'                => $item->id_rpb_sarpras
                    );
                    return $data;
                })
                ->addColumn('supplier', function ($item) {
                    $apv_supplier = "Belum Approve";

                    if($item->apv_supplier > 0) {
                        $apv_supplier = "Approve";
                    }

                    $data = array(
                        'id'            => $item->id_rpb_sarpras,
                        'apv_supplier'  => $apv_supplier
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addPengadaanSarpras(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data);

        $id_rpb_sarpras = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sarana-prasarana/perawatan-sarpras/pengadaan-sarpras/add-pengadaan-sarpras', compact('auth_data', 'data_semester', 'data_unit_kerja', 'data_ruangan', 'data_buku_alat', 'id_rpb_sarpras'));
    }

    public function ajaxGetInventarisByRuangan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data inventaris ruangan
        $data_inventaris = LibDataSarpras::fetchDataInventarisRuangan($auth_data, $input->id_ruangan);

        return $data_inventaris;
    }

    // Supplier
    public function viewDetailSupplier(Request $request, $id_rpb_sarpras){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_rpb_sarpras = LibDataSarpras::fetchDataPengadaanSarpras($auth_data, null, $id_rpb_sarpras, null); 

        return view('sarana-prasarana/perawatan-sarpras/pengadaan-sarpras/view-detail-supplier',compact('auth_data', 'data_rpb_sarpras'));
    }

    public function datatablesPengadaanSarprasSupplier(Request $request, $id_rpb_sarpras)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataPengadaanSarprasSupplier($auth_data, $id_rpb_sarpras, null, 1);

        return Datatables::of($list_data)
                ->addColumn('supplier', function ($item) {
                    return $item->nm_supplier." (".$item->cp_supplier_1.")";
                })
                ->addColumn('harga_supplier', function ($item) {
                    return "Rp".number_format($item->harga_supplier);
                })
                ->addColumn('harga_penawaran', function ($item) {
                    return "Rp".number_format($item->harga_penawaran);
                })
                ->addColumn('harga_approve_supplier', function ($item) {
                    if (! empty($item->harga_approve_supplier)) {
                        return "Rp".number_format($item->harga_approve_supplier);
                    }
                    else {
                        return "";
                    }
                })
                ->addColumn('tgl_approve', function ($item) {
                    if(! empty($item->tgl_approve)) {
                        return strftime("%d %B %Y", strtotime($item->tgl_approve));
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('edit_apv_supplier', function ($item) {
                    $data = array(
                        'id'   => $item->id_rpb_sarpras_supplier
                    );
                    return $data;
                })
                ->addColumn('nm_approve', function ($item) {
                    $tgl_approve = "-";

                    if(! empty($item->tgl_approve)) {
                        $tgl_approve = strftime("%d %B %Y", strtotime($item->tgl_approve));
                    }

                    $data = array(
                        'is_approve'                => $item->is_approve,
                        'nm_approve'                => $item->nm_pengguna,
                        'tgl_approve'               => $tgl_approve,
                        'id_rpb_sarpras_supplier'   => $item->id_rpb_sarpras_supplier
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addPengadaanSarprasSupplier(Request $request, $id_rpb_sarpras){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_rpb_sarpras_supplier = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        $data_rpb_sarpras = LibDataSarpras::fetchDataPengadaanSarpras($auth_data, null, $id_rpb_sarpras, null); 

        $data_supplier = LibDataSarpras::fetchDataSupplier($auth_data);

        return view('sarana-prasarana/perawatan-sarpras/pengadaan-sarpras/add-supplier',compact('auth_data','id_rpb_sarpras_supplier', 'data_rpb_sarpras', 'data_supplier'));

    }

    public function editApvPengadaanSarprasSupplier(Request $request, $id_rpb_sarpras, $id_rpb_sarpras_supplier){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_rpb_sarpras = LibDataSarpras::fetchDataPengadaanSarpras($auth_data, null, $id_rpb_sarpras, null); 

        $data_supplier = LibDataSarpras::fetchDataSupplier($auth_data);

        $data_rpb_sarpras_supplier = LibDataSarpras::fetchDataPengadaanSarprasSupplier($auth_data, $id_rpb_sarpras, $id_rpb_sarpras_supplier, null);

        return view('sarana-prasarana/perawatan-sarpras/pengadaan-sarpras/edit-apv-supplier',compact('auth_data','data_rpb_sarpras', 'data_supplier', 'data_rpb_sarpras_supplier'));

    }


    // Action POST
    public function actionApvPengadaan(Request $request, $mode, $id = null, $id_unit_kerja = null)
    {
        $input = (object) $request->input();
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if ($mode == 'approve-kepala-unit') {
            $unit_kerja = UnitKerja::where('id_unit_kerja','=', $id_unit_kerja)->first();

            $tipe_unit_kerja = $unit_kerja->tipe_unit_kerja;

            if($tipe_unit_kerja == 'SARPRAS') {
                $guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                              ->where('guru.jenis_jabatan', '=', 1)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                $staff = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                              ->where('staff.jenis_jabatan', '=', 1)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();
            }
            elseif($tipe_unit_kerja == 'KEUANGAN') {
                $guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                              ->where('guru.jenis_jabatan', '=', 2)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                $staff = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                              ->where('staff.jenis_jabatan', '=', 2)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();
            }
            else {
                $guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                              ->where('guru.id_unit_kerja', '=', $id_unit_kerja)
                              ->where('guru.jenis_jabatan', '=', 98)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                $staff = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                              ->where('staff.id_unit_kerja', '=', $id_unit_kerja)
                              ->where('staff.jenis_jabatan', '=', 98)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();
            }

            if (! empty($guru->id_pengguna)) {
                // make object to find id
                $rpb_sarpras                           = RpbSarpras::find($id);
                $rpb_sarpras->id_pengguna_kepala_unit  = $guru->id_pengguna;
                $rpb_sarpras->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $rpb_sarpras->updated_at               = $now;
                $rpb_sarpras->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Unit successfully'
              ];
            } elseif (! empty($staff->id_pengguna)) {
                // make object to find id
                $rpb_sarpras                           = RpbSarpras::find($id);
                $rpb_sarpras->id_pengguna_kepala_unit  = $staff->id_pengguna;
                $rpb_sarpras->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $rpb_sarpras->updated_at               = $now;
                $rpb_sarpras->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Unit successfully'
              ];
            } else {
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Kepala Unit '.$unit_kerja->nm_unit_kerja.' Belum Dilakukan Setting!'
                ];
            }
        } 
        elseif ($mode == 'approve-kepala-sarpras') {
            $guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                          ->where('guru.jenis_jabatan', '=', 1)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            $staff = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                          ->where('staff.jenis_jabatan', '=', 1)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            if (! empty($guru->id_pengguna)) {
                // make object to find id
                $rpb_sarpras                               = RpbSarpras::find($id);
                $rpb_sarpras->id_pengguna_kepala_sarpras   = $guru->id_pengguna;
                $rpb_sarpras->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $rpb_sarpras->updated_at                   = $now;
                $rpb_sarpras->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Sarpras successfully'
              ];
            } elseif (! empty($staff->id_pengguna)) {
                // make object to find id
                $rpb_sarpras                               = RpbSarpras::find($id);
                $rpb_sarpras->id_pengguna_kepala_sarpras   = $staff->id_pengguna;
                $rpb_sarpras->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $rpb_sarpras->updated_at                   = $now;
                $rpb_sarpras->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'message' => 'Approve Kepala Sarpras successfully'
                ];
            } else {
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Kepala Unit Sarpras Belum Dilakukan Setting!'
                ];
            }
        } 
        elseif ($mode == 'approve-kepala-sarpras-fix') {
            $rpb_sarpras_supplier = RpbSarprasSupplier::where('id_rpb_sarpras', '=', $id)
                                                    ->where('is_approve', '=', 1)
                                                    ->first();

            if( ! empty($rpb_sarpras_supplier->id_rpb_sarpras_supplier)) {
                $guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                              ->where('guru.jenis_jabatan', '=', 1)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                $staff = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                              ->where('staff.jenis_jabatan', '=', 1)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                if (! empty($guru->id_pengguna)) {
                    // make object to find id
                    $rpb_sarpras                                        = RpbSarpras::find($id);
                    $rpb_sarpras->id_pengguna_kepala_sarpras_approve    = $guru->id_pengguna;
                    $rpb_sarpras->updated_by                            = $input->auth_data->pengguna->id_pengguna;
                    $rpb_sarpras->updated_at                            = $now;
                    $rpb_sarpras->save();

                    return [
                        'status' => 203, // SUCCESS AND LOAD CONTENT
                        'message' => 'Approve Supplier Oleh Kepala Sarpras successfully'
                    ];
                } elseif (! empty($staff->id_pengguna)) {
                    // make object to find id
                    $rpb_sarpras                                        = RpbSarpras::find($id);
                    $rpb_sarpras->id_pengguna_kepala_sarpras_approve    = $staff->id_pengguna;
                    $rpb_sarpras->updated_by                            = $input->auth_data->pengguna->id_pengguna;
                    $rpb_sarpras->updated_at                            = $now;
                    $rpb_sarpras->save();

                    return [
                        'status' => 203, // SUCCESS AND LOAD CONTENT
                        'message' => 'Approve Supplier Oleh Kepala Sarpras successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Kepala Unit Sarpras Belum Dilakukan Setting!'
                    ];
                }
            }
            else {
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Belum Ada Supplier yang Di Approve!'
                ];
            }            
        }
        elseif ($mode == 'approve-supplier') {
            $rpb_sarpras_supplier = RpbSarprasSupplier::find($id);

            $id_rpb_sarpras = $rpb_sarpras_supplier->id_rpb_sarpras;

            $rpb_sarpras_supplier_update = RpbSarprasSupplier::where('id_rpb_sarpras', '=', $id_rpb_sarpras)
                                                    ->get();

            // update semua data menjadi not approve terlebih dahulu
            foreach ($rpb_sarpras_supplier_update as $data) {
                $data->is_approve           = 0;
                $data->id_pengguna_approve  = null;
                $data->tgl_approve          = null; 
                $data->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $data->updated_at           = $now;
                $data->save();
            }
            
            if(! empty($rpb_sarpras_supplier->harga_approve_supplier) && ! empty($rpb_sarpras_supplier->qty_approve_supplier) && ! empty($rpb_sarpras_supplier->termin_approve_supplier)) {
                $rpb_sarpras_supplier->is_approve           = 1;
                $rpb_sarpras_supplier->id_pengguna_approve  = $input->auth_data->pengguna->id_pengguna;
                $rpb_sarpras_supplier->tgl_approve          = $now;
                $rpb_sarpras_supplier->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $rpb_sarpras_supplier->updated_at           = $now;
                $rpb_sarpras_supplier->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'message' => 'Approve Supplier successfully'
                ];
            }
            else {
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Harga, Qty, Termin Approve Supplier Belum Terisi!'
                ];
            } 
            
        }
    }

    // Action POST
    public function actionPengadaanSarpras(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        if($mode == 'add') {
            $validator = Validator::make($request->all(), [
                'id_semester'               => 'required',
                'id_unit_kerja'             => 'required',
               /* 'id_buku_alat' => 'required',
                'id_inventaris_ruangan' => 'required',*/
                'harga_satuan_rpb_sarpras'  => 'required',
                'qty_rpb_sarpras'           => 'required',
                'tgl_rpb_sarpras'           => 'required',
                'prioritas_rpb_sarpras'     => 'required'
            ]);
        }
        elseif($mode == 'add-supplier') {
            $validator = Validator::make($request->all(), [
                'id_rpb_sarpras'            => 'required',
                'id_supplier'               => 'required',
                'harga_supplier'            => 'required',
                'harga_penawaran'           => 'required',
                'qty_penawaran'             => 'required',
                'termin_penawaran'          => 'required'
                /*'harga_approve_supplier'             => 'required',
                'qty_approve_supplier'             => 'required',
                'termin_approve_supplier'             => 'required'*/
            ]);
        }
        elseif($mode == 'edit-apv-supplier') {
            $validator = Validator::make($request->all(), [
                'id_rpb_sarpras'            => 'required',
                'id_supplier'               => 'required',
                'harga_supplier'            => 'required',
                'harga_penawaran'           => 'required',
                'qty_penawaran'             => 'required',
                'termin_penawaran'          => 'required',
                'harga_approve_supplier'    => 'required',
                'qty_approve_supplier'      => 'required',
                'termin_approve_supplier'   => 'required'
            ]);
        }
        
        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                if (! empty($input->id_inventaris_ruangan) or ! empty($input->id_buku_alat)) {
                    // make object to find id
                    $rpbSarpras                             = new RpbSarpras;
                    $rpbSarpras->id_rpb_sarpras             = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $rpbSarpras->id_semester                = $input->id_semester;
                    $rpbSarpras->id_unit_kerja              = $input->id_unit_kerja;
                    if(! empty($input->id_buku_alat)) {
                        $rpbSarpras->id_buku_alat               = $input->id_buku_alat;    
                    }
                    if(! empty($input->id_inventaris_ruangan)) {
                        $rpbSarpras->id_inventaris_ruangan      = $input->id_inventaris_ruangan;
                    }
                    $rpbSarpras->harga_satuan_rpb_sarpras   = $input->harga_satuan_rpb_sarpras;
                    $rpbSarpras->qty_rpb_sarpras            = $input->qty_rpb_sarpras;
                    // convert format date
                    $rpbSarpras->tgl_rpb_sarpras            = date_format(date_create($input->tgl_rpb_sarpras), "Y-m-d H:i:s");
                    $rpbSarpras->prioritas_rpb_sarpras      = $input->prioritas_rpb_sarpras;
                    $rpbSarpras->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $rpbSarpras->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'perawatan-sarpras/pengadaan-sarpras',
                        'message' => 'Input Pengadaan Barang/Sarpras successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Isi Salah Satu (Inventaris Ruangan atau Buku/Alat)'
                    ];
                }
            }
            elseif ($mode == 'add-supplier') {
                // make object to find id
                $rpbSarprasSupplier                             = new RpbSarprasSupplier;
                $rpbSarprasSupplier->id_rpb_sarpras_supplier    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $rpbSarprasSupplier->id_rpb_sarpras             = $input->id_rpb_sarpras;
                $rpbSarprasSupplier->id_supplier                = $input->id_supplier;
                $rpbSarprasSupplier->harga_supplier             = $input->harga_supplier;
                $rpbSarprasSupplier->harga_penawaran            = $input->harga_penawaran;
                $rpbSarprasSupplier->qty_penawaran              = $input->qty_penawaran;
                $rpbSarprasSupplier->termin_penawaran           = $input->termin_penawaran;
                if(! empty($input->harga_approve_supplier)) {
                    $rpbSarprasSupplier->harga_approve_supplier     = $input->harga_approve_supplier;    
                }
                if(! empty($input->qty_approve_supplier)) {
                    $rpbSarprasSupplier->qty_approve_supplier       = $input->qty_approve_supplier;
                }
                if(! empty($input->termin_approve_supplier)) {
                    $rpbSarprasSupplier->termin_approve_supplier    = $input->termin_approve_supplier;
                }
                $rpbSarprasSupplier->created_by                 = $input->auth_data->pengguna->id_pengguna;
                $rpbSarprasSupplier->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'perawatan-sarpras/pengadaan-sarpras/view-detail-supplier/'.$input->id_rpb_sarpras,
                    'message' => 'Input Pengadaan Supplier successfully'
                ];
            }
            elseif ($mode == 'edit-apv-supplier') {
                // make object to find id
                $rpbSarprasSupplier                             = RpbSarprasSupplier::find($id);
                $rpbSarprasSupplier->id_supplier                = $input->id_supplier;
                $rpbSarprasSupplier->harga_supplier             = $input->harga_supplier;
                $rpbSarprasSupplier->harga_penawaran            = $input->harga_penawaran;
                $rpbSarprasSupplier->qty_penawaran              = $input->qty_penawaran;
                $rpbSarprasSupplier->termin_penawaran           = $input->termin_penawaran;
                $rpbSarprasSupplier->harga_approve_supplier     = $input->harga_approve_supplier;
                $rpbSarprasSupplier->qty_approve_supplier       = $input->qty_approve_supplier;
                $rpbSarprasSupplier->termin_approve_supplier    = $input->termin_approve_supplier;
                $rpbSarprasSupplier->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $rpbSarprasSupplier->updated_at                 = $now;
                $rpbSarprasSupplier->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'perawatan-sarpras/pengadaan-sarpras/view-detail-supplier/'.$input->id_rpb_sarpras,
                    'message' => 'Edit Approve Pengadaan Supplier successfully'
                ];
            }
        }
    }
}
