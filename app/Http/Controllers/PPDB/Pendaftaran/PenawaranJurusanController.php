<?php

namespace App\Http\Controllers\PPDB\Pendaftaran;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Penerimaan as Penerimaan;
use App\Models\PenerimaanJurusan as PenerimaanJurusan;
use App\Models\Jurusan as Jurusan;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;

use Validator;
use Carbon\Carbon;

/** 
 * Penawaran Jurusan Controller
 * @author irianto
 */
class PenawaranJurusanController extends Controller
{
    
    /** 
     * View page awal penawaran jurusan, show list data penerimaan 
     * @param Request 
     * @return View
     */
    public function viewPenawaranJurusan(Request $request)
    {        
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        }); 

    	return view('ppdb/pendaftaran/penawaran-jurusan/view-penawaran-jurusan',compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun'));
    }

    /** 
     * Action post view for editing penawaran jurusan
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewPenawaranJurusan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator  = Validator::make($request->all(), [
            'id_penerimaan' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }
        else{
            return [
                'status'    => 204, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$input->id_penerimaan
            ];
        }
    }

    /** 
     * View detail penaran jurusan, to show list jurusan at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function editPenawaranJurusan($id, Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if(!$penerimaan) abort(404);

        /** get penerimaan jurusan by id_penerimaan */
        $penerimaan_jurusan = $this->fetchDataPenerimaanJurusanById($auth_data, $id);

        return view('ppdb/pendaftaran/penawaran-jurusan/edit-penawaran-jurusan',compact('auth_data', 'penerimaan', 'penerimaan_jurusan'));
    }

    /** 
     * View when admin ppdb adding jurusan at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View pilih jurusan
     */
    public function addPenawaranJurusan($id, Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id);

        /** data (id_penerimaan) tidak ditemukan */
        if(!$penerimaan) abort(404);

        /** get data jurusan where not in id_penerimaan */
        $jurusan    = $this->fetchDataJurusan($auth_data, $id);

        return view('ppdb/pendaftaran/penawaran-jurusan/add-penawaran-jurusan',compact('auth_data', 'penerimaan', 'jurusan'));
    }

    /** 
     * action for penawaran penerimaan jurusan (add)
     * @param String id_penerimaan, id_jurusan
     * @return Bool true for success / false for fail
     */
    public function actionAddPenawaranJurusan(Request $request, $id)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_penerimaan' => 'required',
            'id_jurusan'    => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }
        else{
            /** take time now attribute */
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            /** generate id penerimaan jurusan */
            $id_penerimaan_jurusan = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            
            /** action for add data penerimaan */
            $penerimaan_jurusan                         = new PenerimaanJurusan;
            $penerimaan_jurusan->id_penerimaan_jurusan  = $id_penerimaan_jurusan;
            $penerimaan_jurusan->id_penerimaan          = $input->id_penerimaan;
            $penerimaan_jurusan->id_jurusan             = $input->id_jurusan;
            $penerimaan_jurusan->is_aktif               = 0; // default value
            $penerimaan_jurusan->created_by             = $input->auth_data->pengguna->id_pengguna;
            $penerimaan_jurusan->save();

            return [
                'status'    => 202, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$input->id_penerimaan ,
                'message'   => 'Add Jurusan Successfully'
            ];
        }
    }

    /** 
     * Action for deleting penerimaan jurusan
     * @param String id_penarimaan_jurusan
     * @return Bool true for success / false for fail
     */
    public function actionDeletePenawaranJurusan(Request $request, $id)
    {
        $input              = (object) $request->input();
        $penerimaanJurusan  = PenerimaanJurusan::find($id);

        /** validasi (check penerimaan jurusan is exist) */ 
        if($penerimaanJurusan == null) {
            return [
                'status' => 300, // PENERIMAAN JURUSAN NOT EXIST
                'message' => 'Failed To Delete Penerimaan Jurusan'
            ];
        }

        /** deleting data penerimaan jurusan */
        $id_penerimaan                  = $penerimaanJurusan->id_penerimaan;
        $penerimaanJurusan->deleted_by  = $input->auth_data->pengguna->id_pengguna;
        $penerimaanJurusan->save();
        $penerimaanJurusan->delete();

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$id_penerimaan,
            'message' => 'Delete Penerimaan Jurusan Successfully'
        ];
    }

    /** 
     * Action for aktivasi penerimaan jurusan
     * @param String id_penarimaan_jurusan
     * @return Bool true for success / false for fail
     */
    public function actionActivatePenawaranJurusan(Request $request, $id)
    {
        $input              = (object) $request->input();
        $penerimaanJurusan  = PenerimaanJurusan::find($id);

        /** validasi (check penerimaan jurusan is exist) */ 
        if($penerimaanJurusan == null) {
            return [
                'status' => 300, // PENERIMAAN JURUSAN NOT EXIST
                'message' => 'Failed To Activate Penerimaan Jurusan'
            ];
        }

        $now = Carbon::now(env('APP_TIMEZONE', ''));

        /** deleting data penerimaan jurusan */
        $id_penerimaan                  = $penerimaanJurusan->id_penerimaan;
        $penerimaanJurusan->is_aktif    = ($penerimaanJurusan->is_aktif == "0"? "1" : "0");
        $penerimaanJurusan->updated_at  = $now;
        $penerimaanJurusan->updated_by  = $input->auth_data->pengguna->id_pengguna;
        $penerimaanJurusan->save();

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$id_penerimaan,
            'message' => ($penerimaanJurusan->is_aktif == "1" ? 'Activate Penerimaan Jurusan Successfully':'Non-activate Penerimaan Jurusan Successfully')
        ];
    }
    
    /** 
     * Get data jurusan by id and all jurussan
     * @param String id_jurusan
     * @return Object jurusan
     */
    public function fetchDataJurusan($auth_data, $id = null){
        if ($id == null){
            /** get all data jurusan */
            $jurusan = Jurusan::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                        ->orderBy('nm_jurusan', 'asc')->get();
        } else {
            /** get all data jurusan */
            $jurusan = Jurusan::whereNotIn('id_jurusan', function($jurusan) use ($id)
            {
               $jurusan->from('penerimaan_jurusan')
                ->selectRaw('id_jurusan')
                ->where('deleted_at', null)
                ->where('id_penerimaan', '=', $id);
            })
            ->get();
        }

        return $jurusan;
    }

    /** 
     * Get data penerimaan jurusan by id
     * @param String id_penerimaan
     * @return Object penerimaan_jurusan
     */
    public function fetchDataPenerimaanJurusanById($auth_data, $id) {
        $penerimaan_jurusan = PenerimaanJurusan::select('penerimaan_jurusan.id_penerimaan_jurusan', 'penerimaan_jurusan.is_aktif', 'jurusan.nm_jurusan')
                                    ->where('penerimaan_jurusan.id_penerimaan','=',$id)
                                    ->leftJoin('jurusan','jurusan.id_jurusan','=','penerimaan_jurusan.id_jurusan')
                                    ->get();

        return $penerimaan_jurusan;
    }
}
