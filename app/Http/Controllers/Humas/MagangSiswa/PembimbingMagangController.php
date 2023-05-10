<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Imports\DataImportExcel;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengambilanMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\RekananMagang;
use App\Models\Semester;
use Auth;
use Excel;
use DB;
use Session;
use Validator;


class PembimbingMagangController extends Controller
{
    public function viewPembimbingMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);
        $semester_aktif =  Semester::where('is_aktif_semester', '1')->first();
        return view('humas/magang-siswa/pembimbing-magang/view-pembimbing-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang', 'semester_aktif'));
    }

    // public function importExcel(Request $request)
    // {

    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
    //     $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);

    //     return view('humas/magang-siswa/pengajuan-magang/import-excel', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang'));
    // }

    // public function importExcelAction(Request $request)
    // {

    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;
    //     $now = Carbon::now(env('APP_TIMEZONE', ''));

    //     $validator = Validator::make($request->all(), [
    //         'id_rekanan_magang' => 'required',
    //         'id_periode_magang' => 'required',
    //         'file-excel' => 'required',
    //     ]);

    //     if ($validator->fails() && $mode != 'delete') {
    //         return [
    //             'status' => 300, // FAILED
    //             'message' => $validator->errors()->first()
    //         ];
    //     } else {

    //         if ($request->hasFile('file-excel')) {

    //             $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
    //             $data = $data[0]; // Sheet 1

    //             if (count($data)) {

    //                 DB::beginTransaction();

    //                 try {

    //                     foreach ($data as $key => $value) {
    //                         $value = (object) $value;

    //                         $siswa = Siswa::where('nis_siswa', $value->nis)->first();

    //                         if ($siswa) {

    //                             if ($value->status == 'Waiting Approval') {
    //                                 $status = 0;
    //                             } elseif ($value->status == 'Approve') {
    //                                 $status = 1;
    //                             } else {
    //                                 return [
    //                                     'status'    => 300, // FAILED
    //                                     'message'   => "Mohon maaf status yang diizinkan hanya approve dan waiting approval"
    //                                 ];
    //                             }

    //                             $data                        = new PengajuanSiswaMagang;
    //                             $data->id_pengambilan_magang = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
    //                             $data->id_siswa              = $siswa->id_siswa;
    //                             $data->id_kelas              = $siswa->id_kelas;
    //                             $data->id_periode_magang     = $input->id_periode_magang;
    //                             $data->id_rekanan_magang     = $input->id_rekanan_magang;
    //                             $data->status_apv_pengambilan_magang         = $status;
    //                             $data->status_magang         = 0;
    //                             $data->created_by            = $input->auth_data->pengguna->id_pengguna;
    //                             $data->save();
    //                         } else {

    //                             return [
    //                                 'status'    => 300, // FAILED
    //                                 'message'   => "Mohon maaf siswa dengan nis " . $value->nis . " ini tidak ditemikan"
    //                             ];
    //                         }
    //                     }

    //                     DB::commit();

    //                     return [
    //                         'status' => 202, // SUCCESS AND LOAD CONTENT
    //                         'path' => 'magang-siswa/pengajuan-magang',
    //                         'message' => 'Import Magang Siswa Successfully'
    //                     ];
    //                 } catch (\Exception $e) {

    //                     DB::rollback();

    //                     return [
    //                         'status'    => 203, // GAGAL
    //                         'message'       => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error'
    //                     ];
    //                 }
    //             } else {

    //                 return [
    //                     'status'    => 300, // FAILED
    //                     'message'   => "File excel anda kosong"
    //                 ];
    //             }
    //         } else {
    //             return [
    //                 'status'    => 300, // FAILED
    //                 'message'   => "File Excel tidak ditemukan"
    //             ];
    //         }
    //     }
    // }

    public function datatablesPembimbingMagang(Request $request)
    {

        $input = (object) $request->input();
        // $auth_data = $input->auth_data;

        $id_periode_magang = $input->id_periode_magang;

        $data_pengambilan_magang = PengambilanMagang::where('id_periode_magang', $id_periode_magang)->get()->toArray();
        $id_pengambilan_magang =  collect($data_pengambilan_magang)->unique('id_rekanan_magang')->pluck('id_pengambilan_magang');

        $data = PengambilanMagang::whereIn('id_pengambilan_magang', $id_pengambilan_magang)->with('periodeMagang.semester', 'rekanan');

        return Datatables::of($data)
            ->addColumn('semester', function ($item) {
                return $item->periodeMagang->semester->tahun_ajaran . " " . $item->periodeMagang->semester->nm_semester;
            })
            ->addColumn('periode', function ($item) {
                return $item->periodeMagang->nm_periode_magang;
            })
            // ->addColumn('status_apv_pengambilan_magang', function ($item) {
            //     if ($item->status_apv_pengambilan_magang == 0) {
            //         return "Belum di Approve";
            //     } elseif ($item->status_apv_pengambilan_magang == 1) {
            //         return "Sudah di Approve";
            //     } elseif ($item->status_apv_pengambilan_magang == 2) {
            //         return "Waiting Approval";
            //     } elseif ($item->status_apv_pengambilan_magang == 3) {
            //         return "Tidak di Approve";
            //     }
            // })
            // ->addColumn('status_magang', function ($item) {
            //     if ($item->status_magang == 0) {
            //         return "";
            //     } elseif ($item->status_magang == 1) {
            //         return "Sudah Selesai Magang";
            //     } else {
            //         return "Pemagang Dibatalkan";
            //     }
            // })

            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pengambilan_magang
                    // 'status_apv_pengambilan_magang' => $item->status_apv_pengambilan_magang,
                    // 'id_siswa' => $item->id_siswa
                );

                return $data;
            })
            ->make(true);
    }

    // public function addPengajuanMagang(Request $request, $id_rekanan_magang, $id_periode_magang)
    // {

    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data, $id_rekanan_magang);
    //     $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data, $id_periode_magang);

    //     return view('humas/magang-siswa/pengajuan-magang/add-pengajuan-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang'));
    // }

    // public function datatablesListSiswa(Request $request, $id_rekanan_magang, $id_periode_magang)
    // {

    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $data_siswa = Siswa::select('pengambilan_magang.id_pengambilan_magang', 'pengambilan_magang.id_rekanan_magang', 'pengambilan_magang.id_periode_magang', 'pengambilan_magang.status_apv_pengambilan_magang', 'pengambilan_magang.status_magang', 'siswa.id_siswa', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas')
    //         ->leftJoin('pengambilan_magang', function ($join) use ($id_rekanan_magang, $id_periode_magang) {
    //             $join->on('pengambilan_magang.id_siswa', '=', 'siswa.id_siswa')
    //                 ->where('pengambilan_magang.status_magang', '<>', 10)
    //                 ->whereNull('pengambilan_magang.deleted_at')
    //                 ->where('pengambilan_magang.id_rekanan_magang', '=', $id_rekanan_magang)
    //                 ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang);
    //         })
    //         ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
    //         ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
    //         ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
    //         ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
    //         ->where('status_pengguna.aktif_status_pengguna', '=', 1)
    //         ->orderBy('kelas.tingkat', 'asc')
    //         ->orderBy('kelas.nm_kelas', 'asc')
    //         ->orderBy('siswa.nis_siswa', 'asc');

    //     return Datatables::of($data_siswa)
    //         ->addColumn('status_apv_pengambilan_magang', function ($item) {
    //             if ($item->status_apv_pengambilan_magang) {
    //                 if ($item->status_apv_pengambilan_magang == 0) {
    //                     return "Belum di Approve";
    //                 } elseif ($item->status_apv_pengambilan_magang == 1) {
    //                     return "Sudah di Approve";
    //                 } elseif ($item->status_apv_pengambilan_magang == 2) {
    //                     return "Waiting Approval";
    //                 } elseif ($item->status_apv_pengambilan_magang == 3) {
    //                     return "Tidak di Approve";
    //                 }
    //             } else {
    //                 return '';
    //             }
    //         })
    //         ->addColumn('action', function ($item) {
    //             $data = array(
    //                 'id' => $item->id_siswa,
    //                 'id_pengambilan_magang' => $item->id_pengambilan_magang,
    //                 'status_apv_pengambilan_magang' => $item->status_apv_pengambilan_magang
    //             );

    //             return $data;
    //         })
    //         ->make(true);
    // }

    // public function actionPengajuanMagang(Request $request)
    // {

    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $now = Carbon::now(env('APP_TIMEZONE', ''));

    //     $id_pengambilan_magang = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

    //     if (!isset($input->id_pengambilan_magang)) {
    //         $siswa  = Siswa::find($input->id_siswa);
    //     }

    //     if ($input->mode == 'pengajuan') {

    //         $data                        = new PengajuanSiswaMagang;
    //         $data->id_pengambilan_magang = $id_pengambilan_magang;
    //         $data->id_siswa              = $input->id_siswa;
    //         $data->id_kelas              = $siswa->id_kelas;
    //         $data->id_periode_magang     = $input->id_periode_magang;
    //         $data->id_rekanan_magang     = $input->id_rekanan_magang;
    //         $data->status_apv_pengambilan_magang         = $input->status_apv_pengambilan_magang;
    //         $data->status_magang         = 0;
    //         $data->created_by            = $input->auth_data->pengguna->id_pengguna;
    //         $data->save();

    //         $message = 'Pengajuan Siswa Magang Successfully';
    //     } elseif ($input->mode == 'pengubahan-status-magang') {
    //         $data = PengajuanSiswaMagang::find($input->id_pengambilan_magang);
    //         $data->status_magang = $input->status_magang;
    //         $data->updated_by            = $input->auth_data->pengguna->id_pengguna;
    //         $data->save();

    //         $message = 'Perngubahan Status Magang Successfully';
    //     } elseif ($input->mode == 'tidak-diapprove') {

    //         if (isset($input->id_pengambilan_magang)) {

    //             $data = PengajuanSiswaMagang::find($input->id_pengambilan_magang);
    //         } else {

    //             $data = PengajuanSiswaMagang::where([
    //                 'id_siswa' => $siswa->id_siswa,
    //                 'id_rekanan_magang' => $input->id_rekanan_magang,
    //                 'id_periode_magang' => $input->id_periode_magang
    //             ])->first();
    //         }

    //         $data->status_apv_pengambilan_magang = $input->status_apv_pengambilan_magang;
    //         $data->keterangan_approval = $input->keterangan;
    //         $data->updated_by            = $input->auth_data->pengguna->id_pengguna;
    //         $data->save();

    //         $message = 'Perngubahan Status Tidak Diapprove Successfully';
    //     } elseif ($input->mode == 'hapus-data') {

    //         if (isset($input->id_pengambilan_magang)) {

    //             $data = PengajuanSiswaMagang::find($input->id_pengambilan_magang);
    //         } else {

    //             $data = PengajuanSiswaMagang::where([
    //                 'id_siswa' => $siswa->id_siswa,
    //                 'id_rekanan_magang' => $input->id_rekanan_magang,
    //                 'id_periode_magang' => $input->id_periode_magang
    //             ])->first();
    //         }

    //         $data->deleted_by            = $input->auth_data->pengguna->id_pengguna;
    //         $data->save();
    //         $data->delete();

    //         $message = 'Penghapusan Data Successfully';
    //     }

    //     return [
    //         'status' => 205, // SUCCESS AND LOAD TABLE
    //         'message' => $message
    //     ];
    // }
}
