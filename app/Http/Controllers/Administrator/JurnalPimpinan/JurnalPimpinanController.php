<?php

namespace App\Http\Controllers\Administrator\JurnalPimpinan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JurnalPimpinan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\LaporanJurnalPimpinan;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

use Auth;
use DB;
use Session;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Yajra\Datatables\Datatables;

class JurnalPimpinanController extends Controller
{
    public function viewSettingJurnalPimpinan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('administrator/jurnal-pimpinan/setting-jurnal-pimpinan/view-setting-jurnal-pimpinan', compact('auth_data'));
    }

    public function addSettingJurnalPimpinan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('administrator/jurnal-pimpinan/setting-jurnal-pimpinan/view-add-setting-jurnal-pimpinan', compact('auth_data'));
    }

    public function editSettingJurnalPimpinan(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = JurnalPimpinan::select('guru.nip_guru', 'staff.nip_staff', 'pengguna.nm_pengguna', 'jurnal_pimpinan.id_jurnal_pimpinan', 'jurnal_pimpinan.is_aktif')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'jurnal_pimpinan.id_pengguna')
            ->leftjoin('guru', 'guru.id_pengguna', '=', 'jurnal_pimpinan.id_pengguna')
            ->leftjoin('staff', 'staff.id_pengguna', '=', 'jurnal_pimpinan.id_pengguna')
            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->where('jurnal_pimpinan.id_jurnal_pimpinan', '=', $id)->first();

        return view('administrator/jurnal-pimpinan/setting-jurnal-pimpinan/view-edit-setting-jurnal-pimpinan', compact('auth_data', 'guru'));
    }

    public function datatablesSettingJurnalPimpinan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = JurnalPimpinan::selectRaw('ukg.nm_unit_kerja AS unit_kerja_guru, uks.nm_unit_kerja AS unit_kerja_staff')
            ->addSelect('guru.nip_guru', 'staff.nip_staff', 'pengguna.nm_pengguna', 'jurnal_pimpinan.id_jurnal_pimpinan', 'jurnal_pimpinan.is_aktif')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'jurnal_pimpinan.id_pengguna')
            ->leftjoin('guru', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            ->leftjoin('staff', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
            ->leftjoin('unit_kerja AS ukg', 'ukg.id_unit_kerja', '=', 'guru.id_unit_kerja')
            ->leftjoin('unit_kerja AS uks', 'uks.id_unit_kerja', '=', 'staff.id_unit_kerja')
            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('nip_pengguna', function ($item) {
                if (!empty($item->nip_guru)) {
                    return $item->nip_guru;
                } else {
                    return $item->nip_staff;
                }
            })
            ->addColumn('nm_unit_kerja', function ($item) {
                if (!empty($item->unit_kerja_guru)) {
                    return $item->unit_kerja_guru;
                } else {
                    return $item->unit_kerja_staff;
                }
            })
            ->addColumn('is_aktif', function ($item) {
                if ($item->is_aktif == 1) {
                    return "Aktif";
                } else {
                    return "Non-Aktif";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jurnal_pimpinan
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesAddJurnalPimpinan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = Pengguna::selectRaw('ukg.nm_unit_kerja AS unit_kerja_guru')
            ->addSelect('pengguna.nm_pengguna', 'guru.nip_guru', 'pengguna.id_pengguna')
            ->leftjoin('guru', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            // ->leftjoin('staff', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
            ->leftjoin('unit_kerja AS ukg', 'ukg.id_unit_kerja', '=', 'guru.id_unit_kerja')
            // ->leftjoin('unit_kerja AS uks', 'uks.id_unit_kerja', '=', 'staff.id_unit_kerja')
            ->where('pengguna.status_join_table', 2)
            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('jurnal_pimpinan')
                    ->whereRaw('jurnal_pimpinan.id_pengguna = pengguna.id_pengguna');
            })
            ->get();

        return Datatables::of($list_data)
            ->addColumn('nip_pengguna', function ($item) {

                return $item->nip_guru;
            })
            ->addColumn('nm_unit_kerja', function ($item) {

                return $item->unit_kerja_guru;
            })
            ->addColumn('is_aktif', function ($item) {
                if ($item->is_aktif == "1") {
                    return "Aktif";
                } else {
                    return "Tidak aktif";
                }
            })
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id' => $item->id_pengguna
                );
                return $data;
            })
            ->make(true);
    }

    public function actionSettingJurnalPimpinan(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), []);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    foreach ($input->id_pengguna as $id_pengguna) {
                        $guru                            = new JurnalPimpinan();
                        $guru->id_jurnal_pimpinan        = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $guru->id_pengguna                 = $id_pengguna;
                        $guru->created_by                = $input->auth_data->pengguna->id_pengguna;
                        $guru->created_at                = $now;
                        $guru->is_aktif                 = 1;
                        $guru->save();
                    }
                    DB::commit();
                    return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'message' => 'Tambah Jurnal Pimpinan Berhasil',
                        'path' => 'jurnal-pimpinan/tambah-jurnal-pimpinan/'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Tambah Jurnal Pimpinan Gagal'
                    ];
                }
            } elseif ($mode == "edit") {
                $guru         = JurnalPimpinan::find($id);
                $guru->is_aktif    = $input->is_aktif;
                $guru->updated_at    = $now;
                $guru->updated_by    = $input->auth_data->pengguna->id_pengguna;
                $guru->save();

                return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'message' => 'Edit Jurnal Pimpinan Berhasil',
                    'path' => 'jurnal-pimpinan/tambah-jurnal-pimpinan/'
                ];
            } elseif ($mode == "delete") {
                $guru     = JurnalPimpinan::find($id);
                $guru->forceDelete();
                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Pengguna Berhasil Dihapus'
                ];
            }
        }
    }

    public function viewLaporanAllJurnalPimpinan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('administrator/jurnal-pimpinan/laporan/laporan-data-jurnal-pimpinan', compact('auth_data'));
    }

    public function previewFile($id, Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($id);
        $laporan_kerja_harian = LaporanJurnalPimpinan::findOrFail($id);

        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);

        return view('administrator/jurnal-pimpinan/laporan/preview-file-jurnal-pimpinan', compact('auth_data', 'laporan_kerja_harian', 'link', 'ext'));
    }


    public function downloadFile(Request $request, $id = null)
    {
        $input = (object) $request->input();
        $laporan_kerja_harian = LaporanJurnalPimpinan::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);

        return Storage::disk('spaces')->download($laporan_kerja_harian->path_file, $laporan_kerja_harian->nm_file . "." . $ext);
    }

    public function datatablesLaporanJurnalPimpinan(Request $request)
    {

        $input = (object) $request->input();
        // $auth_data = $input->auth_data;

        $list_data = LaporanJurnalPimpinan::with('pengguna')->orderBy('created_at', 'desc');

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
                    'id'        => $item->id_laporan_jurpin,
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
