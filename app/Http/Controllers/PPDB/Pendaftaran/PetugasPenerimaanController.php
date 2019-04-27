<?php

namespace App\Http\Controllers\PPDB\Pendaftaran;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;
use App\Models\PenerimaanPetugas as PenerimaanPetugas;
use App\Models\Pengguna as Pengguna;

use Validator;
use Carbon\Carbon;

/** 
 * Petugas Penerimaan PPDB Controller
 * @author irianto
 */
class PetugasPenerimaanController extends Controller
{
    /** 
     * View page awal petugas penerimaan, show list data penerimaan 
     * @param Request 
     * @return View
     */
    public function viewPetugasPenerimaan(Request $request)
    {        
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });; 

        return view('ppdb/pendaftaran/petugas-penerimaan/view-petugas-penerimaan',compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun'));
    }

    /** 
     * Action post view for editing petugas penerimaan
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewPetugasPenerimaan(Request $request)
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
                'path'      => 'pendaftaran/petugas-penerimaan/'.$input->id_penerimaan
            ];
        }
    }

    /** 
     * View detail petugas penerimaan, to show petugas penerimaan at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function petugasPenerimaan(Request $request, $id_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

        /** data (id_penerimaan) tidak ditemukan */
        if(!$penerimaan) abort(404);

        /** query for list petugas penerimaan by id_penerimaan */
        $penerimaan_petugas = PenerimaanPetugas::select(
                                    'penerimaan_petugas.id_penerimaan_petugas',
                                    'penerimaan_petugas.jabatan_petugas',
                                    'penerimaan_petugas.id_pengguna_petugas', 
                                    'pengguna.status_join_table',
                                    'pengguna.nm_pengguna', 
                                    'guru.nip_guru', 
                                    'staff.nip_staff')
                                ->leftJoin('pengguna','pengguna.id_pengguna','=','penerimaan_petugas.id_pengguna_petugas')
                                ->leftJoin('staff', 'staff.id_pengguna', '=', 'pengguna.id_pengguna')
                                ->leftJoin('guru', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
                                ->whereIn('pengguna.status_join_table', array(1, 2,))
                                ->where('id_penerimaan', '=', $id_penerimaan)
                                ->get();

        return view('ppdb/pendaftaran/petugas-penerimaan/petugas-penerimaan',compact('auth_data', 'penerimaan', 'penerimaan_petugas'));
    }

    /** 
     * add view add petugas penerimaan 
     * @param Request 
     * @return View
     */
    public function addPetugasPenerimaan(Request $request, $id_penerimaan)
    {        
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get list pengguna */
        $pengguna = 
            Pengguna::select('pengguna.id_pengguna', 'pengguna.nm_pengguna', 'guru.nip_guru', 'staff.nip_staff', 'pengguna.status_join_table')
                ->leftJoin('staff', 'staff.id_pengguna', '=', 'pengguna.id_pengguna')
                ->leftJoin('guru', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
                ->whereIn('pengguna.status_join_table', array(1, 2,))
                ->whereNotIn('pengguna.id_pengguna', function($pengguna) use ($id_penerimaan)
                    {
                    $pengguna->from('penerimaan_petugas')
                        ->selectRaw('id_pengguna_petugas')
                        ->where('deleted_at', null)
                        ->where('id_penerimaan', '=', $id_penerimaan);
                    })
                ->get();

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

        return view('ppdb/pendaftaran/petugas-penerimaan/add-petugas-penerimaan',compact('auth_data', 'penerimaan', 'pengguna'));
    }

    /** 
     * action for petugas penerimaan jurusan (add)
     * @param String id_penerimaan, id_petugas
     * @return Bool true for success / false for fail
     */
    public function actionAddPetugasPenerimaan(Request $request, $id)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_penerimaan'         => 'required',
            'id_pengguna_petugas'   => 'required',
            'jabatan_petugas'       => 'required'
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
            $id_penerimaan_petugas = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            
            /** action for add data penerimaan */
            $penerimaan_petugas                         = new PenerimaanPetugas;
            $penerimaan_petugas->id_penerimaan_petugas  = $id_penerimaan_petugas;
            $penerimaan_petugas->id_penerimaan          = $input->id_penerimaan;
            $penerimaan_petugas->id_pengguna_petugas    = $input->id_pengguna_petugas;
            $penerimaan_petugas->jabatan_petugas        = $input->jabatan_petugas;
            $penerimaan_petugas->created_by             = $input->auth_data->pengguna->id_pengguna;
            $penerimaan_petugas->save();

            return [
                'status'    => 202, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/petugas-penerimaan/'.$input->id_penerimaan ,
                'message'   => 'Add Petugas Successfully'
            ];
        }
    }

    /** 
     * Action for deleting petugas penerimaan
     * @param String id_penerimaan,id_penerimaan_petugas
     * @return Bool true for success / false for fail
     */
    public function actionDeletePetugasPenerimaan(Request $request, $id_penerimaan, $id_penerimaan_petugas)
    {
        $input              = (object) $request->input();
        $penerimaan_petugas  = PenerimaanPetugas::find($id_penerimaan_petugas);

        /** validasi (check petugas penerimaan is exist) */ 
        if($penerimaan_petugas == null) {
            return [
                'status' => 300, // PENERIMAAN JURUSAN NOT EXIST
                'message' => 'Failed To Delete penerimaan petugas'
            ];
        }

        /** deleting data petugas penerimaan */
        $penerimaan_petugas->deleted_by  = $input->auth_data->pengguna->id_pengguna;
        $penerimaan_petugas->save();
        $penerimaan_petugas->delete();

        return [
            'status'  => 202, // SUCCESS AND LOAD CONTENT
            'path'    => 'pendaftaran/petugas-penerimaan/'.$id_penerimaan,
            'message' => 'Delete Petugas Penerimaan Successfully'
        ];
    }
}
