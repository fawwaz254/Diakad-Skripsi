<?php

namespace App\Http\Controllers\Guru\KetidaksesuaianSOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPelanggaran;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KetidaksesuaianSOP;
use App\Models\Pengguna;
use Auth;
use DB;
use Session;
use Validator;


class InputKetidaksesuaianSOPController extends Controller
{
    public function viewInputKetidaksesuaianSOP(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/ketidaksesuaian-sop/view-input-ketidaksesuaian-sop', compact('auth_data'));
    }

    public function addInputKetidaksesuaianSOP(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/ketidaksesuaian-sop/add-input-ketidaksesuaian-sop', compact('auth_data'));
    }

    // public function editInputPelanggaran($id, Request $request) {
    //     # code...
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     // get id_guru
    //     $guru = Guru::select('id_guru')
    //         ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
    //         ->first();

    //     $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    //     // ambil data all kelas
    //     $data_kelas = LibKelas::fetchDataKelas($auth_data);

    //     $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

    //     // ambil data all kategori
    //     $data_kategori = KategoriPelanggaran::with('subkategori_pelanggaran')->where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();

    //     $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, null, $id);

    //     $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $data_pelanggaran_siswa->id_siswa);

    //     // ambil data siswa sekelas
    //     $data_siswa_sekelas = LibSiswa::fetchDataSiswa($auth_data, isset($data_siswa->id_kelas) ? $data_siswa->id_kelas : null);

    //     // convert format date
    //     $tgl_pelanggaran = strftime( "%d %B %Y %H:%M:%S", strtotime($data_pelanggaran_siswa->tgl_pelanggaran));

    //     return view('guru/guru-piket/input-pelanggaran/edit-input-pelanggaran',compact('auth_data', 'data_kelas', 'data_semester','data_siswa','data_kategori','data_pelanggaran_siswa','tgl_pelanggaran', 'data_siswa_sekelas'));

    // }

    public function ajaxGetPengguna(Request $request)
    {
        # code...
        $input = (object) $request->input();
        // $auth_data = $input->auth_data;

        if ($input->unitKerja == 'guru') {
            $pengguna = Pengguna::where('status_join_table', 2)->get();
        } elseif ($input->unitKerja == 'tendik') {
            $pengguna = Pengguna::where('status_join_table', 1)->get();
        } else {
            $pengguna = Pengguna::get();
        }
        // $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $input->kelas);

        return $pengguna;
    }

    public function datatablesInputKetidaksesuaianSOP(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KetidaksesuaianSOP::where('id_pengguna_input', $auth_data->pengguna->id_pengguna)->with('pengguna');

        return Datatables::of($list_data)
            ->addColumn('tgl_pelanggaran', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_pelanggaran));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pelanggaran_siswa,
                    'is_sudah_tindakan' => $item->is_sudah_tindakan
                );
                return $data;
            })
            ->make(true);
    }

    // // Action POST
    public function actionInputKetidaksesuaianSOP(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_pengguna'               => 'required',
            'catatan'                   => 'required',
            'tgl'                       => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $data                               = new KetidaksesuaianSOP();
                $data->id_ketidaksesuaian_sop       = $id;
                $data->id_pengguna                  = $input->id_pengguna;
                $data->id_pengguna_input            = $input->auth_data->pengguna->id_pengguna;
                $data->catatan_pelanggaran          = $input->catatan;
                $data->tgl_pelanggaran              = date_format(date_create($input->tgl), "Y-m-d H:i:s");
                $data->created_by                   = $input->auth_data->pengguna->id_pengguna;

                if ($request->hasFile('file')) {

                    $validator = Validator::make($request->all(), [
                        'file' => 'mimes:jpeg,jpg,png,pdf|required|max:5120'
                    ]);

                    if ($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    } else {
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/ketidaksesuaiansop/' . $id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;
                    }
                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ketidaksesuaian-sop/input-ketidaksesuaian-sop',
                    'message' => 'Save Data Ketidaksesuaian SOP Successfully'
                ];
            } elseif ($mode == 'edit') {
                // $siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first();

                // // make object to find id
                // $pelanggaranSiswa                               = PelanggaranSiswa::find($id);
                // $pelanggaranSiswa->id_siswa                     = $input->id_siswa;
                // $pelanggaranSiswa->id_kelas                     = $siswa->id_kelas;
                // $pelanggaranSiswa->id_semester                  = $input->id_semester;
                // $pelanggaranSiswa->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                // $pelanggaranSiswa->catatan_pelanggaran          = $input->catatan_pelanggaran;
                // // convert format date
                // $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tgl_pelanggaran), "Y-m-d H:i:s");
                // $pelanggaranSiswa->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                // $pelanggaranSiswa->updated_at                   = $now;
                // $pelanggaranSiswa->save();

                // return [
                //     'status' => 202, // SUCCESS AND LOAD CONTENT
                //     'path' => $load_url . '/input-pelanggaran',
                //     'message' => 'Update Pelanggaran Siswa Successfully'
                // ];
            } elseif ($mode == 'delete') {
                // if ($tindakanPelanggaran = TindakanPelanggaran::where('id_pelanggaran_siswa', $id)->first()) {
                //     return [
                //         'status' => 300, // SUCCESS AND LOAD TABLE
                //         'message' => 'Failed To Delete Pelanggaran Siswa'
                //     ];
                // } else {
                //     // make object to find id
                //     $pelanggaranSiswa               = PelanggaranSiswa::find($id);
                //     $pelanggaranSiswa->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                //     $pelanggaranSiswa->save();

                //     $pelanggaranSiswa->delete();

                //     return [
                //         'status' => 203, // SUCCESS AND LOAD TABLE
                //         'message' => 'Delete Pelanggaran Siswa Successfully'
                //     ];
                // }
            }
        }
    }
}
