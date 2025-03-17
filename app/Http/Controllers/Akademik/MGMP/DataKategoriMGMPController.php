<?php

namespace App\Http\Controllers\Akademik\MGMP;

use Illuminate\Support\Facades\Hash;

use App\Models\CategoriFileGuru;
use App\Models\CategoriFileMGMP;
use App\Models\CategoryFileRole;
use App\Models\LaporanKerjaHarianMGMP;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Pengguna;
use App\Models\Semester;
use App\Models\SubCategoryFileMGMP;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;



class DataKategoriMGMPController extends BaseController
{

    public function viewDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_pengguna;

        return view('akademik/mgmp/data-kategori/view-data-kategori', compact('auth_data'));
    }

    public function datatablesCategoryfile(Request $request)
    {
        //$list_data = CategoryFileGuru::all();

        // $input = (object) $request->input();
        // dd($input->auth_data);
        // $auth_data = $input->auth_data->role_aktif->id_pengguna;
        // $list_data = CategoryFileGuru::join('category_file_mgmp', 'category_file.category_file_mgmp_id', '=', 'category_file_guru.category_file_mgmp_id')
        //     ->where('category_file_guru.id_pengguna', $auth_data)
        //     ->select('category_file_mgmp.*')->get();

        ///////////////////////////

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $list_data = CategoriFileMGMP::all();
        $list_data = '';
        $list_data = CategoriFileMGMP::with('category_file_guru.pengguna')->get();
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->category_file_mgmp_id
                );
                return $data;
            })
            ->editColumn('is_aktif', function ($item) {
                return $item->is_aktif == '1' ? "Aktif" : "Tidak Aktif";
            })
            ->addColumn('guru', function ($item) {
                $data = [];
                if ($item->category_file_guru ?? false) {
                    foreach ($item->category_file_guru as $key => $value) {
                        $data[$key]['guru'] = $item->category_file_guru[$key]->pengguna->nm_pengguna;
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
        $auth_data = $input->auth_data;
        $mata_pelajaran = MataPelajaran::isAktif()->get();


        $pengguna = pengguna::where('status_join_table', 2)
            ->with('status_pengguna')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();


        // $pengguna = Pengguna::where('status_join_table', 2)->get();
        // $pengguna = Role::where('id_role', '<>', '14')->get();
        return view('akademik/mgmp/data-kategori/add-data-kategori', compact('auth_data', 'pengguna', 'mata_pelajaran'));
    }

    public function editDataKategori(Request $request, $category_file_id = null)
    {
        // dd($category_file_id);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $mata_pelajaran = MataPelajaran::isAktif()->get();
        $name = CategoriFileMGMP::where('category_file_mgmp_id', $category_file_id)->first();
        $data_kategori = CategoriFileGuru::where('category_file_mgmp_id', $category_file_id)->first();
        // $allowed_role = CategoryFileRole::where('category_file_id', $data_kategori->category_file_mgmp_id)->pluck('id_role');
        // $pengguna = Pengguna::where('status_join_table', 2)->get();
        $pengguna = pengguna::where('status_join_table', 2)
            ->with('status_pengguna')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();
        $allowed_role_pengguna = CategoriFileGuru::where('category_file_mgmp_id', $category_file_id)->pluck('id_pengguna')->toArray();

        return view('akademik/mgmp/data-kategori/edit-data-kategori', compact('auth_data', 'data_kategori', 'pengguna', 'allowed_role_pengguna', 'mata_pelajaran', 'name'));
    }

    //action POST
    public function actionDataKategori(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'category_file_name' => 'required',
            'category_file_explanation' => 'required',
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

                if ($input->allowed_guru ?? false) {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $datakategori = new CategoriFileMGMP;
                    $datakategori->category_file_mgmp_id = $id;
                    $datakategori->category_file_name = $input->category_file_name;
                    $datakategori->category_file_explanation = $input->category_file_explanation;
                    $datakategori->is_aktif = 1;
                    $datakategori->created_by = $input->auth_data->pengguna->id_pengguna;
                    $datakategori->save();

                    foreach ($input->allowed_guru as $key => $value) {
                        $uuid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $datakategori_guru = new CategoriFileGuru;
                        $datakategori_guru->category_file_guru_id = $uuid;
                        $datakategori_guru->id_pengguna = $input->allowed_guru[$key];
                        $datakategori_guru->category_file_mgmp_id = $datakategori->category_file_mgmp_id;
                        //  $datakategori_guru->category_file_mgmp_id = $datakategori->category_file_mgmp_id;
                        $datakategori_guru->save();
                    }

                    // $uuid1 = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    // $subkategori = new SubCategoryFileMGMP(); 
                    // $subkategori->sub_category_file_id = $uuid1;
                    // $subkategori->sub_category_file_name = 'Folder Akademik';
                    // $subkategori->sub_category_file_explanation = 'untuk mengupload file original';
                    // $subkategori->category_file_mgmp_id = $datakategori->category_file_mgmp_id ;
                    // $subkategori->save();

                    // $uuid2 = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    // $subkategori2 = new SubCategoryFileMGMP(); 
                    // $subkategori2->sub_category_file_id = $uuid2;
                    // $subkategori2->sub_category_file_name = 'Folder Guru';
                    // $subkategori2->sub_category_file_explanation = 'untuk mengupload file guru';
                    // $subkategori2->category_file_mgmp_id = $datakategori->category_file_mgmp_id ;
                    // $subkategori2->save();


                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'mpmp/data-kategori-mapel',
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
                $datakategori = CategoriFileMGMP::find($id);
                $datakategori->category_file_name = $input->category_file_name;
                $datakategori->category_file_explanation = $input->category_file_explanation;
                $datakategori->is_aktif = $input->is_aktif;
                $datakategori->updated_by = $input->auth_data->pengguna->id_pengguna;
                $datakategori->updated_at = $now;
                $datakategori->save();

                // replace category file role
                CategoriFileGuru::where('category_file_mgmp_id', $id)->delete();
                $now = Carbon::now();
                foreach ($input->allowed_guru as $key => $value) {
                    $uuid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $datakategori_role = new CategoriFileGuru();
                    $datakategori_role->category_file_guru_id = $uuid;
                    $datakategori_role->id_pengguna = $input->allowed_guru[$key];
                    $datakategori_role->category_file_mgmp_id = $id;
                    $datakategori_role->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'mpmp/data-kategori-mapel',
                    'message' => 'Update Data Kategori Succesfully'
                ];
            } elseif ($mode == 'delete') {
                if (!CategoriFileMGMP::where('category_file_mgmp_id', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Delete Data Kategori'
                    ];
                } else {
                    // make object to find id 
                    // $categoriFile = CategoriFileMGMP::where('category_file_mgmp_id', $id)->first();


                    $dataguru = CategoriFileGuru::where('category_file_mgmp_id', $id)->get();

                    foreach ($dataguru as $guru) {
                        $data = CategoriFileGuru::where('category_file_guru_id', $guru->category_file_guru_id)->first();
                        $data->delete();
                    }

                    $datakategori = CategoriFileMGMP::where('category_file_mgmp_id', $id)->first();
                    $datakategori->deleted_by = $input->auth_data->pengguna->id_pengguna;
                    $datakategori->save();

                    $datakategori->delete();

                    $datakategori_role = CategoryFileRole::where('category_file_id', $id)->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Kategori succesfully'

                    ];
                }
            }
        }
    }
    public function viewLaporanAllMGMP(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = Pengguna::where('status_join_table', 2)->orderBy('nm_pengguna', 'asc')->get(['id_pengguna', 'nm_pengguna']);

        return view('akademik/mgmp/data-kategori/laporan-data-mgmp', compact('auth_data', 'data'));
    }

    public function showDataByUserId($id_pengguna, Request $request)
    {

        $tahunAjaran = $request->query('tahun-ajaran');
        $semester = $request->query('semester');

        $datas = LaporanKerjaHarianMGMP::with(['mapel', 'pengguna'])
            ->where('id_pengguna', $id_pengguna)
            ->orderBy('created_at', 'desc');

        if ($tahunAjaran) {
            $datas->whereYear('tanggal', $tahunAjaran);
        }

        if ($semester) {
            $semesterMonths = $semester === "ganjil" ? [1, 2, 3, 4, 5, 6] : [7, 8, 9, 10, 11, 12];
            $datas->whereIn(DB::raw('MONTH(tanggal)'), $semesterMonths);
        }

        $datas = $datas->get();

        if (count($datas) === 0) {
            return abort(404);
        }

        return view('akademik/mgmp/data-kategori/cetak-data-mgmp-by-id', compact('datas', 'semester', 'tahunAjaran'));
    }


    public function previewFile($id, Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_kerja_harian = LaporanKerjaHarianMGMP::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);
        return view('akademik/mgmp/data-kategori/preview-file-mgmp', compact('auth_data', 'laporan_kerja_harian', 'link', 'ext'));
    }

    public function downloadFile(Request $request, $id = null)
    {
        $input = (object) $request->input();
        // $file_pengguna = FilePengguna::where('file_pengguna_id', $id)->first();
        $laporan_kerja_harian = LaporanKerjaHarianMGMP::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);

        return Storage::disk('spaces')->download($laporan_kerja_harian->path_file, $laporan_kerja_harian->nm_file . "." . $ext);
    }

    public function datatablesKerjaHarianAllMGMP(Request $request)
    {
        $input = (object) $request->input();
        $id_pengguna = $request->input('id_pengguna');
        $tahun = $request->input('tahun');
        $semester = $request->input('semester');

        $query = LaporanKerjaHarianMGMP::with('mapel', 'pengguna');


        if ($id_pengguna) {
            $query->where('id_pengguna', $id_pengguna);
        }

        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        if ($semester) {
            $semesterMonths = $semester === "ganjil" ? [1, 2, 3, 4, 5, 6] : [7, 8, 9, 10, 11, 12];
            $query->whereIn(DB::raw('MONTH(tanggal)'), $semesterMonths);
        }

        $list_data = $query->orderBy('created_at', 'desc')
            ->get();

        $list_data->each(function ($item, $index) {
            $item->index_column = $index + 1;
        });

        return Datatables::of($list_data)
            ->editColumn('tanggal', function ($item) {
                return Carbon::parse($item->tanggal)->format('d M Y');
            })
            ->addColumn('action', function ($item) {
                if ($item->path_file) {
                    $file = Storage::disk('spaces')->url($item->path_file);
                    $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);
                    $note = in_array($ext, ['pdf', 'doc', 'docx']) ? 'file' : 'image';
                } else {
                    $file = null;
                    $note = null;
                }

                return [
                    'id' => $item->id_laporan_kerja_harian_mgmp,
                    'jenis' => $item->jenis,
                    'status' => $item->status,
                    'file' => $file,
                    'note' => $note,
                ];
            })
            ->addColumn('nm_pengguna', function ($item) {
                return $item->pengguna->nm_pengguna;
            })
            ->make(true);
    }
}
