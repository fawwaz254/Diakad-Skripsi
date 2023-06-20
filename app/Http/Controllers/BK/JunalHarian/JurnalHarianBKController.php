<?php

namespace App\Http\Controllers\BK\JunalHarian;

use App\Http\Controllers\Controller;
use App\Models\CategoryJurnalHarianTendik;
use App\Models\LaporanKerjaHarianTendik;
use App\Models\Siswa;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Carbon\Carbon;

class JurnalHarianBKController extends Controller
{
    public function viewLaporanJurnalHarian(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('bk/jurnal-harian/tambah-jurnal-harian/view-tambah-jurnal-harian', compact('auth_data'));
    }
    public function addLaporanHarianJurnalHarian(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $waktu = Carbon::today()->toDateString();
        $all_siswa = Siswa::with('kelas', 'pengguna')->whereHas('kelas')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('nm_status_pengguna', '=', 'AKTIF');
        })->orderBy('nis_siswa')->get();
        return view('bk/jurnal-harian/tambah-jurnal-harian/add-tambah-jurnal-harian', compact('auth_data',  'waktu', 'all_siswa'));
    }
    public function previewFile($id, Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_kerja_harian = LaporanKerjaHarianTendik::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);
        return view('bk/jurnal-harian/tambah-jurnal-harian/priview-file-tambah-jurnal-harian', compact('auth_data', 'laporan_kerja_harian', 'link', 'ext', 'id'));
    }

    public function downloadFile(Request $request, $id = null)
    {

        $laporan_kerja_harian = LaporanKerjaHarianTendik::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);

        return Storage::disk('spaces')->download($laporan_kerja_harian->path_file, $laporan_kerja_harian->nm_file . "." . $ext);
    }

    public function actionLaporanHarian(Request $request, $mode = 0, $id = 0)
    {
        $input = (object) $request->input();

        $list_validator = [
            'tanggal'           => 'required',
            'jenis'            => 'required',
            'status'            => 'required',
            'keterangan'         => 'required',
        ];

        $validator = Validator::make($request->all(), $list_validator);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $category_jurnal_harian_tendik = CategoryJurnalHarianTendik::whereHas('unit_kerja', function ($q) {
                    $q->where('nm_unit_kerja', 'BK');
                })->first();

                if ($category_jurnal_harian_tendik) {
                    $data                               = new LaporanKerjaHarianTendik();
                    $data->id_lap_kerha_t               = $id;
                    $data->tanggal                      = date_format(date_create($input->tanggal), "Y-m-d");
                    $data->jenis                        = $input->jenis;
                    $data->id_category_jh_tendik        = $category_jurnal_harian_tendik->id_category_jh_tendik;
                    $data->keterangan_progres           = $input->keterangan;
                    $data->status                       = $input->status;
                    $data->catatan                      = $input->keterangan;
                    $data->id_pengguna                  = $input->auth_data->pengguna->id_pengguna;
                    $data->id_siswa                     = $input->id_siswa;
                    $data->created_by                   = $input->auth_data->pengguna->id_pengguna;

                    if ($request->hasFile('file')) {
                        $validator = Validator::make($request->all(), [
                            'file' => 'mimes:pptx,docx,doc,xlsx,jpeg,jpg,png,pdf|required|max:5120'
                        ]);

                        if ($validator->fails()) {
                            return [
                                'status' => 300, // FAILED
                                'message' => $validator->errors()->first()
                            ];
                        } else {
                            $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                            $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/tendik/' . $id, request()->file, 'public');
                            $data->path_file = $file;

                            $upload = $request->file('file');
                            $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                            $data->nm_file = $filename;
                        }
                    }
                    $data->save();
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'jurnal-harian/tambah-jurnal-harian',
                        'message' => 'Save Jurnal Harian Successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Save Failed'
                    ];
                }
            } elseif ($mode == 'edit') {
                $data                               = LaporanKerjaHarianTendik::find($id);
                $data->tanggal                      = date_format(date_create($input->tanggal), "Y-m-d");
                $data->jenis                        = $input->jenis;
                $data->keterangan_progres           = $input->keterangan;
                $data->status                       = $input->status;
                $data->catatan                      = $input->keterangan;
                $data->id_siswa                     = $input->id_siswa;
                $data->updated_by                   = $input->auth_data->pengguna->id_pengguna;

                if ($request->hasFile('file')) {
                    $validator = Validator::make($request->all(), [
                        'file' => 'mimes:pptx,docx,doc,xlsx,jpeg,jpg,png,pdf|required|max:5120'
                    ]);
                    if ($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    } else {
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/tendik/' . $id, request()->file, 'public');
                        $data->path_file = $file;
                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;
                    }
                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'jurnal-harian/tambah-jurnal-harian',
                    'message' => 'Update Jurnal Harian Successfully'
                ];
            } elseif ($mode == 'delete') {

                $data               = LaporanKerjaHarianTendik::find($id);
                $data->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $data->save();
                $data->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Jurnal Harian Successfully'
                ];
            }
        }
    }

    public function datatablesKerjaHarian(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanKerjaHarianTendik::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->orderBy('created_at', 'desc');

        return Datatables::of($list_data)
            ->editColumn('tanggal', function ($item) {
                $date = Carbon::parse($item->tanggal)->locale('id');
                $date->settings(['formatFunction' => 'translatedFormat']);
                return $date->format('l, j F Y ');
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


    public function editKerjaHarian(Request $request, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $laporan_kerja_harian_tendik = LaporanKerjaHarianTendik::findOrFail($id);
        $all_siswa = Siswa::with('kelas', 'pengguna')->whereHas('kelas')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('nm_status_pengguna', '=', 'AKTIF');
        })->orderBy('nis_siswa')->get();
        return view('bk/jurnal-harian/tambah-jurnal-harian/edit-tambah-jurnal-harian', compact('auth_data', 'laporan_kerja_harian_tendik', 'all_siswa'));
    }
}
