<?php

namespace App\Http\Controllers\Keuangan\Rapb;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Rapb as Rapb;
use App\Models\Realisasi as Realisasi;
use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use App\Models\UnitKerja as UnitKerja;
use App\Models\SubkategoriRapb as SubkategoriRapb;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class InputRapbController extends BaseController
{
    public function viewInputRapb(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('keuangan/rapb/input-rapb/view-input-rapb', compact('auth_data', 'data_semester'));
    }
    
    public function actionViewInputRapb(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester_mulai'   => 'required',
            'id_semester_selesai'  => 'required'
        ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/input-rapb/view-detail-input-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai
                ];
        }
    }

    public function viewDetailInputRapb(Request $request, $id_semester_mulai, $id_semester_selesai)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        
        return view('keuangan/rapb/input-rapb/view-detail-input-rapb', compact('auth_data', 'id_semester_mulai', 'id_semester_selesai', 'data_semester'));
    }

    public function datatablesInputRapb(Request $request, $id_semester_mulai, $id_semester_selesai)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $jenis_jabatan = '-';
        if ($staff = Staff::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first()) {
            $jenis_jabatan = $staff->jenis_jabatan;
        }


        if ($guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first()) {
            $jenis_jabatan = $guru->jenis_jabatan;
        }

        $list_data = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, null, "1");

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran_mulai." (".$item->nm_semester_mulai.") - ".$item->tahun_ajaran_selesai." (".$item->nm_semester_selesai.")";
                })
                ->addColumn('tipe_kategori_rapb', function ($item) {
                    if ($item->tipe_kategori_rapb == 1) {
                        return "Penerimaan";
                    } elseif ($item->tipe_kategori_rapb == 2) {
                        return "Pengeluaran";
                    }
                })
                ->addColumn('dana_perkiraan_rapb', function ($item) {
                    return "Rp".number_format($item->dana_perkiraan_rapb);
                })
                ->addColumn('tgl_rapb', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_rapb));
                })
                ->addColumn('prioritas_rapb', function ($item) {
                    if ($item->prioritas_rapb == 1) {
                        return "Rendah";
                    } elseif ($item->prioritas_rapb == 2) {
                        return "Sedang";
                    } elseif ($item->prioritas_rapb == 3) {
                        return "Tinggi";
                    }
                })
                ->addColumn('nm_kepala_unit', function ($item) {
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rapb'        => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_keuangan', function ($item) {
                    $data = array(
                        'nm_kepala_keuangan'  => $item->nm_kepala_keuangan,
                        'id_rapb'             => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('action', function ($item) use ($jenis_jabatan) {
                    $data = array(
                        'id' => $item->id_rapb,
                        'jenis_jabatan' => $jenis_jabatan
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addInputRapb(Request $request, $id_semester_mulai, $id_semester_selesai)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_rapb = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        $semester_mulai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_mulai);

        $semester_selesai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_selesai);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        return view('keuangan/rapb/input-rapb/add-input-rapb', compact('auth_data', 'id_rapb', 'semester_mulai', 'semester_selesai', 'data_unit_kerja'));
    }

    public function ajaxGetKategoriByJenis(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data subkategori by kategori
        $data_kategori = LibDataKeuangan::fetchDataKategoriRapb($auth_data, $input->jenis);

        return $data_kategori;
    }

    public function ajaxGetSubkategoriByKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data subkategori by kategori
        $data_subkategori = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $input->id_kategori_rapb);

        return $data_subkategori;
    }

    public function editInputRapb(Request $request, $id_semester_mulai, $id_semester_selesai, $id_rapb)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $semester_mulai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_mulai);

        $semester_selesai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_selesai);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $data_rapb = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id_rapb);

        $kategori = SubkategoriRapb::select('kategori_rapb.id_kategori_rapb', 'kategori_rapb.tipe_kategori_rapb')
                            ->join('kategori_rapb', 'kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                            ->where('subkategori_rapb.id_subkategori_rapb', '=', $data_rapb->id_subkategori_rapb)
                            ->first();

        $jenis_kategori = $kategori->tipe_kategori_rapb;

        $id_kategori_rapb = $kategori->id_kategori_rapb;

        $data_kategori = LibDataKeuangan::fetchDataKategoriRapb($auth_data, $jenis_kategori);

        $data_subkategori = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb);

        // convert format date
        $tgl_rapb = strftime("%A, %d %B %Y", strtotime($data_rapb->tgl_rapb));

        return view('keuangan/rapb/input-rapb/edit-input-rapb', compact('auth_data', 'semester_mulai', 'semester_selesai', 'data_unit_kerja', 'data_rapb', 'jenis_kategori', 'id_kategori_rapb', 'data_kategori', 'data_subkategori', 'tgl_rapb'));
    }


    // Action POST
    public function actionApvRapb(Request $request, $mode, $id = null, $id_unit_kerja = null)
    {
        $input = (object) $request->input();
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if ($mode == 'approve-kepala-unit') {
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

            if (! empty($guru->id_pengguna)) {
                // make object to find id
                $rapb                           = Rapb::find($id);
                $rapb->id_pengguna_kepala_unit  = $guru->id_pengguna;
                $rapb->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $rapb->updated_at               = $now;
                $rapb->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Unit successfully'
              ];
            } elseif (! empty($staff->id_pengguna)) {
                // make object to find id
                $rapb                           = Rapb::find($id);
                $rapb->id_pengguna_kepala_unit  = $staff->id_pengguna;
                $rapb->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $rapb->updated_at               = $now;
                $rapb->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Unit successfully'
              ];
            } else {
                $unit_kerja = UnitKerja::where('id_unit_kerja', '=', $id_unit_kerja)->first();

                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Kepala Unit '.$unit_kerja->nm_unit_kerja.' Belum Dilakukan Setting!'
                ];
            }
        } elseif ($mode == 'approve-kepala-keuangan') {
            $guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                          ->where('guru.jenis_jabatan', '=', 2)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            $staff = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                          ->where('staff.jenis_jabatan', '=', 2)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            if (! empty($guru->id_pengguna)) {
                // make object to find id
                $rapb                               = Rapb::find($id);
                $rapb->id_pengguna_kepala_keuangan  = $guru->id_pengguna;
                $rapb->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $rapb->updated_at                   = $now;
                $rapb->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Keuangan successfully'
              ];
            } elseif (! empty($staff->id_pengguna)) {
                // make object to find id
                $rapb                               = Rapb::find($id);
                $rapb->id_pengguna_kepala_keuangan  = $staff->id_pengguna;
                $rapb->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $rapb->updated_at                   = $now;
                $rapb->save();

                return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Keuangan successfully'
              ];
            } else {
                return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Kepala Unit Keuangan Belum Dilakukan Setting!'
                ];
            }
        }
    }

    // Action POST
    public function actionInputRapb(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_semester_mulai' => 'required',
            'id_semester_selesai' => 'required',
            'id_subkategori_rapb' => 'required',
            'id_unit_kerja' => 'required',
            'dana_perkiraan_rapb' => 'required',
            'tgl_rapb' => 'required',
            'prioritas_rapb' => 'required'
        ]);
        
        if ($validator->fails() && in_array($mode, ['add','edit'])) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $rapb                         = new Rapb;
                $rapb->id_rapb                = $id;
                $rapb->id_semester_mulai      = $input->id_semester_mulai;
                $rapb->id_semester_selesai    = $input->id_semester_selesai;
                $rapb->id_subkategori_rapb    = $input->id_subkategori_rapb;
                $rapb->id_unit_kerja          = $input->id_unit_kerja;
                $rapb->dana_perkiraan_rapb    = $input->dana_perkiraan_rapb;
                $rapb->tgl_rapb               = date_format(date_create($input->tgl_rapb), "Y-m-d");
                $rapb->prioritas_rapb         = $input->prioritas_rapb;
                $rapb->created_by             = $input->auth_data->pengguna->id_pengguna;
                $rapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/input-rapb/view-detail-input-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai,
                    'message' => 'Input RAPB successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $rapb                         = Rapb::find($id);
                $rapb->id_semester_mulai      = $input->id_semester_mulai;
                $rapb->id_semester_selesai    = $input->id_semester_selesai;
                $rapb->id_subkategori_rapb    = $input->id_subkategori_rapb;
                $rapb->id_unit_kerja          = $input->id_unit_kerja;
                $rapb->dana_perkiraan_rapb    = $input->dana_perkiraan_rapb;
                $rapb->tgl_rapb               = date_format(date_create($input->tgl_rapb), "Y-m-d");
                $rapb->prioritas_rapb         = $input->prioritas_rapb;
                $rapb->updated_by             = $input->auth_data->pengguna->id_pengguna;
                $rapb->updated_at             = $now;
                $rapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/input-rapb/view-detail-input-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai,
                    'message' => 'Input RAPB successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($realisasi = Realisasi::where('id_rapb', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'RAPB Sudah Terpakai Pada Realisasi!'
                    ];
                } else {
                    // make object to find id
                    $rapb               = Rapb::find($id);
                    $rapb->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $rapb->save();

                    $rapb->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete RAPB successfully'
                    ];
                }
            }
        }
    }
}
