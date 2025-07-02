<?php

namespace App\Http\Controllers\PPDB\Pendaftaran;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Penerimaan as Penerimaan;
use App\Models\PenerimaanSyarat as PenerimaanSyarat;
use App\Models\Jurusan as Jurusan;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

use Validator;
use Carbon\Carbon;

/** 
 * Syarat Penerimaan PPDB Controller
 * @author irianto
 */
class SyaratPenerimaanController extends Controller
{
    /** 
     * View page awal syarat penerimaan, show list data penerimaan 
     * @param Request 
     * @return View
     */
    public function viewSyaratPenerimaan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });;

        return view('ppdb/pendaftaran/syarat-penerimaan/view-syarat-penerimaan', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun'));
    }

    /** 
     * Action post view for editing syarat penerimaan
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewSyaratPenerimaan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        $validator  = Validator::make($request->all(), [
            'id_penerimaan' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            return [
                'status'    => 204, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/syarat-penerimaan/' . $input->id_penerimaan
            ];
        }
    }

    /** 
     * View detail syarat penerimaan, to show syarat penerimaan at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function syaratPenerimaan($id, Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) abort(404);

        /** retrive data syarat penerimaan */
        $penerimaan_syarat = PenerimaanSyarat::select('id_penerimaan_syarat', 'id_penerimaan', 'penerimaan_syarat.id_jurusan', 'nm_penerimaan_syarat', 'keterangan_penerimaan_syarat', 'is_wajib', 'is_upload_file', 'urutan', 'jurusan.nm_jurusan')
            ->where('penerimaan_syarat.id_penerimaan', '=', $id)
            ->orderBy('penerimaan_syarat.urutan', 'asc')
            ->leftJoin('jurusan', 'jurusan.id_jurusan', '=', 'penerimaan_syarat.id_jurusan')
            ->get();

        $penerimaan_syarat_umum = $penerimaan_syarat->where('id_jurusan', null)->all();
        $penerimaan_syarat_khusus = $penerimaan_syarat->where('id_jurusan', '!=', null)->all();

        return view('ppdb/pendaftaran/syarat-penerimaan/syarat-penerimaan', compact('auth_data', 'penerimaan', 'penerimaan_syarat_umum', 'penerimaan_syarat_khusus'));
    }

    /** 
     * View when admin ppdb adding syarat penerimaan at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View pilih jurusan
     */
    public function addSyaratPenerimaan($id, Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) abort(404);

        /** get all data jurusan */
        $jurusan = Jurusan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('nm_jurusan', 'asc')->get();

        return view('ppdb/pendaftaran/syarat-penerimaan/add-syarat-penerimaan', compact('auth_data', 'penerimaan', 'request', 'jurusan'));
    }

    /** 
     * action for add syarat penerimaan jurusan (add)
     * @param String id_penerimaan
     * @return Bool true for success / false for fail
     */
    public function actionAddSyaratPenerimaan(Request $request, $id)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        $validator = Validator::make($request->all(), [
            'id_penerimaan'                 => 'required',
            'nm_penerimaan_syarat'          => 'required',
            'keterangan_penerimaan_syarat'  => 'required',
            'is_wajib'                      => 'required|boolean',
            'is_upload_file'                => 'required|boolean',
            'urutan'                        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            /** take time now attribute */
            $now = Carbon::now();

            /** get penerimaan by id */
            $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

            /** data (id_penerimaan) tidak ditemukan */
            if (!$penerimaan) return [
                'status'    => 300, // FAILED
                'message'   => 'Id data penerimaan tidak valid'
            ];

            /** generate id_penerimaan_syarat */
            $id_penerimaan_syarat = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

            /** action for add data syarat penerimaan */
            $penerimaan_syarat                                  = new PenerimaanSyarat;
            $penerimaan_syarat->id_penerimaan_syarat            = $id_penerimaan_syarat;
            $penerimaan_syarat->id_penerimaan                   = $id;
            $penerimaan_syarat->id_jurusan                      = ($input->type == "khusus" ? $input->id_jurusan : null);
            $penerimaan_syarat->nm_penerimaan_syarat            = $input->nm_penerimaan_syarat;
            $penerimaan_syarat->is_wajib                        = $input->is_wajib;
            $penerimaan_syarat->urutan                          = $input->urutan;
            $penerimaan_syarat->is_upload_file                  = $input->is_upload_file;
            $penerimaan_syarat->keterangan_penerimaan_syarat    = $input->keterangan_penerimaan_syarat;
            $penerimaan_syarat->created_by                      = auth_data()->pengguna->id_pengguna;
            $penerimaan_syarat->save();

            return [
                'status'    => 202, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/syarat-penerimaan/' . $input->id_penerimaan,
                'message'   => 'Add Syarat Penerimaan Successfully'
            ];
        }
    }

    /** 
     * View when admin ppdb editing syarat penerimaan at spesific syarat penerimaan
     * @param String id_penerimaan_syarat
     * @return View
     */
    public function editSyaratPenerimaan(Request $request, $id_penerimaan, $id_penerimaan_syarat)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        /** get syarat penerimaan by id_penerimaan_syarat */
        $input              = (object) $request->input();
        $penerimaan_syarat  = PenerimaanSyarat::find($id_penerimaan_syarat);

        /** validasi (check syarat penerimaan is exist) */
        if ($penerimaan_syarat == null) return 'Wrong Id Syarat Penerimaan';

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) abort(404);

        /** get all data jurusan */
        $jurusan = Jurusan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('nm_jurusan', 'asc')->get();

        return view('ppdb/pendaftaran/syarat-penerimaan/edit-syarat-penerimaan', compact('auth_data', 'penerimaan', 'penerimaan_syarat', 'request', 'jurusan'));
    }

    /** 
     * Action post for edit syarat penerimaan
     * @param String id_penerimaan_syarat
     * @return Bool true for success / false for fail
     */
    public function actionEditSyaratPenerimaan(Request $request, $id_penerimaan, $id_syarat_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = auth_data();

        $validator = Validator::make($request->all(), [
            'id_penerimaan'                 => 'required',
            'nm_penerimaan_syarat'          => 'required',
            'keterangan_penerimaan_syarat'  => 'required',
            'is_wajib'                      => 'required|boolean',
            'is_upload_file'                => 'required|boolean',
            'urutan'                        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }

        $penerimaan_syarat  = PenerimaanSyarat::find($id_syarat_penerimaan);

        /** validasi (check penerimaan jurusan is exist) */
        if ($penerimaan_syarat == null) {
            return [
                'status' => 300, // PENERIMAAN JURUSAN NOT EXIST
                'message' => 'Failed To Edit Syarat Penerimaan'
            ];
        }

        $now = Carbon::now();

        /** updating data syarat penerimaan */
        $penerimaan_syarat->id_jurusan                      = ($input->type == "khusus" ? $input->id_jurusan : null);
        $penerimaan_syarat->nm_penerimaan_syarat            = $input->nm_penerimaan_syarat;
        $penerimaan_syarat->is_wajib                        = $input->is_wajib;
        $penerimaan_syarat->urutan                          = $input->urutan;
        $penerimaan_syarat->is_upload_file                  = $input->is_upload_file;
        $penerimaan_syarat->keterangan_penerimaan_syarat    = $input->keterangan_penerimaan_syarat;
        $penerimaan_syarat->updated_at                      = $now;
        $penerimaan_syarat->updated_by                      = auth_data()->pengguna->id_pengguna;
        $penerimaan_syarat->save();

        return [
            'status'    => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'pendaftaran/syarat-penerimaan/' . $id_penerimaan,
            'message'   => 'Edit Syarat Penerimaan Successfully'
        ];
    }

    /** 
     * Action for deleting syarat penerimaan
     * @param String id_penerimaan_syarat
     * @return Bool true for success / false for fail
     */
    public function actionDeleteSyaratPenerimaan(Request $request, $id_penerimaan, $id_syarat_penerimaan)
    {
        $input              = (object) $request->input();
        $penerimaan_syarat  = PenerimaanSyarat::find($id_syarat_penerimaan);

        /** validasi (check penerimaan jurusan is exist) */
        if ($penerimaan_syarat == null) {
            return [
                'status' => 300, // PENERIMAAN JURUSAN NOT EXIST
                'message' => 'Failed To Delete Syarat Penerimaan'
            ];
        }

        /** deleting data syarat penerimaan */
        $penerimaan_syarat->deleted_by  = auth_data()->pengguna->id_pengguna;
        $penerimaan_syarat->save();
        $penerimaan_syarat->delete();

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'pendaftaran/syarat-penerimaan/' . $id_penerimaan,
            'message' => 'Delete Syarat Penerimaan Successfully'
        ];
    }
}
