<?php

namespace App\Http\Controllers\Humas\JurnalHarian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CategoryJurnalHarianTendik;
use App\Models\CategoryKelompokJurnalHarianTendik;
use App\Models\LaporanKerjaHarianTendik;
use App\Models\MataPelajaran;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Validator;

class DataKategoriJurnalHarianController extends Controller
{
    public function viewDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data()->role_aktif->id_pengguna;

        return view('humas/jurnal-harian/data-kategori/view-data-kategori', compact('auth_data'));
    }

    public function datatablesCategoryfile(Request $request)
    {
        $input = (object) $request->input();

        $list_data = CategoryJurnalHarianTendik::with('category_kelompok_jurnal_harian_tendik.pengguna', 'unit_kerja')->get();
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_category_jh_tendik
                );
                return $data;
            })
            ->addColumn('tendik', function ($item) {
                $data = [];
                if ($item->category_kelompok_jurnal_harian_tendik ?? false) {
                    foreach ($item->category_kelompok_jurnal_harian_tendik as $key => $value) {
                        $data[$key]['tendik'] = $item->category_kelompok_jurnal_harian_tendik[$key]->pengguna->nm_pengguna;
                    }
                }
                return $data;
            })
            ->make(true);
    }

    public function addDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $unit_kerja = UnitKerja::all();


        $pengguna = Pengguna::where('status_join_table', 1)
            ->where('nm_pengguna', '!=', 'Admin Diakad')
            ->with('status_pengguna')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();


        // $pengguna = Pengguna::where('status_join_table', 2)->get();
        // $pengguna = Role::where('id_role', '<>', '14')->get();
        return view('humas/jurnal-harian/data-kategori/add-data-kategori', compact('auth_data', 'pengguna', 'unit_kerja'));
    }

    public function editDataKategori(Request $request, $id_category_jh_tendik = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $unit_kerja = UnitKerja::all();
        $name = CategoryJurnalHarianTendik::find($id_category_jh_tendik)->first();
        $data_kategori = CategoryKelompokJurnalHarianTendik::where('id_category_jh_tendik', $id_category_jh_tendik)->first();
        $pengguna = pengguna::where('status_join_table', 1)
            ->where('nm_pengguna', '!=', 'Admin Diakad')
            ->with('status_pengguna')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();
        $allowed_role_pengguna = CategoryKelompokJurnalHarianTendik::where('id_category_jh_tendik', $id_category_jh_tendik)->pluck('id_pengguna')->toArray();

        return view('humas/jurnal-harian/data-kategori/edit-data-kategori', compact('auth_data', 'data_kategori', 'pengguna', 'allowed_role_pengguna', 'unit_kerja', 'name'));
    }

    // //action POST
    public function actionDataKategori(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_unit_kerja' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            //mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                if ($input->allowed_tendik ?? false) {
                    $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                    $datakategori                               = new CategoryJurnalHarianTendik();
                    $datakategori->id_category_jh_tendik        = $id;
                    $datakategori->id_unit_kerja                = $input->id_unit_kerja;
                    $datakategori->description                  = $input->description;
                    $datakategori->created_by                   = auth_data()->pengguna->id_pengguna;
                    $datakategori->save();

                    foreach ($input->allowed_tendik as $key => $value) {
                        $uuid = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                        $datakategori_tendik = new CategoryKelompokJurnalHarianTendik();
                        $datakategori_tendik->id_c_k_jh_tendik = $uuid;
                        $datakategori_tendik->id_pengguna = $input->allowed_tendik[$key];
                        $datakategori_tendik->id_category_jh_tendik =  $datakategori->id_category_jh_tendik;
                        $datakategori_tendik->save();
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'jurnal-harian/kelompok-jurnal-harian-tendik',
                        'message' => 'Save Data Kategori Succesfully'
                    ];
                } else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Create Data Kategori'
                    ];
                }
            } elseif ($mode == 'edit') {
                // make object to find id
                $datakategori                               = CategoryJurnalHarianTendik::find($id);
                $datakategori->id_unit_kerja                = $input->id_unit_kerja;
                $datakategori->description                  = $input->description;
                $datakategori->updated_by                   = auth_data()->pengguna->id_pengguna;
                $datakategori->updated_at                   = $now;
                $datakategori->save();

                // replace category file role
                CategoryKelompokJurnalHarianTendik::where('id_category_jh_tendik', $id)->delete();
                $now = Carbon::now();
                foreach ($input->allowed_tendik as $key => $value) {
                    $uuid = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                    $datakategori_role = new CategoryKelompokJurnalHarianTendik();
                    $datakategori_role->id_c_k_jh_tendik = $uuid;
                    $datakategori_role->id_pengguna = $input->allowed_tendik[$key];
                    $datakategori_role->id_category_jh_tendik = $id;
                    $datakategori_role->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'jurnal-harian/kelompok-jurnal-harian-tendik',
                    'message' => 'Update Data Kategori Succesfully'
                ];
            } elseif ($mode == 'delete') {
                // dd('sdaasd');
                if (!CategoryJurnalHarianTendik::where('id_category_jh_tendik', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Delete Data Kategori'
                    ];
                } else {

                    $datatendik = CategoryKelompokJurnalHarianTendik::where('id_category_jh_tendik', $id)->get();
                    // dd($datatendik);
                    foreach ($datatendik as $tendik) {
                        $data =    CategoryKelompokJurnalHarianTendik::where('id_c_k_jh_tendik', $tendik->id_c_k_jh_tendik)->first();
                        $data->delete();
                    }
                    // make object to find id 
                    $datakategori                       = CategoryJurnalHarianTendik::where('id_category_jh_tendik', $id)->first();
                    $datakategori->deleted_by           = auth_data()->pengguna->id_pengguna;
                    $datakategori->save();

                    $datakategori->delete();

                    // $datakategori_role = CategoryFileRole::where('category_file_id', $id)->delete();
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Kategori succesfully'

                    ];
                }
            }
        }
    }
    public function viewLaporanAllJurnalHarian(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('humas/jurnal-harian/data-kategori/laporan-data-jurnal-harian', compact('auth_data'));
    }

    public function previewFile($id, Request $request)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $laporan_kerja_harian = LaporanKerjaHarianTendik::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);
        return view('humas/jurnal-harian/data-kategori/preview-file-jurnal-harian', compact('auth_data', 'laporan_kerja_harian', 'link', 'ext'));
    }

    public function downloadFile(Request $request, $id = null)
    {
        $laporan_kerja_harian = LaporanKerjaHarianTendik::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);

        return Storage::disk('spaces')->download($laporan_kerja_harian->path_file, $laporan_kerja_harian->nm_file . "." . $ext);
    }

    public function datatablesKerjaHarianJurnalHarian(Request $request)
    {
        $list_data = LaporanKerjaHarianTendik::with('category_jurnal_harian_tendik.unit_kerja', 'pengguna')->orderBy('created_at', 'desc');

        return Datatables::of($list_data)
            ->editColumn('tanggal', function ($item) {
                return Carbon::parse($item->tanggal)->format('d M Y');
            })
            ->addColumn('action', function ($item) {
                if ($item->path_file) {
                    $file =  Storage::disk('spaces')->url($item->path_file);
                    $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);
                    if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx') {
                        $note = 'file';
                    } else {
                        $note = 'image';
                    }
                } else {
                    $file = null;
                    $note = null;
                }

                $data = array(
                    'id'        => $item->id_lap_kerha_t,
                    'jenis'    => $item->jenis,
                    'status'    => $item->status,
                    'file'      => $file,
                    'note'      => $item->catatan,
                );
                return $data;
            })
            ->make(true);
    }
}
