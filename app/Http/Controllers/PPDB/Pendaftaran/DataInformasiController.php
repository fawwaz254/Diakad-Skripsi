<?php

namespace App\Http\Controllers\PPDB\Pendaftaran;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\InformasiPpdb as InformasiPpdb;

use Validator;
use Carbon\Carbon;

/** 
 * Data Informasi PPDB Controller
 * @author irianto
 */
class DataInformasiController extends Controller
{

    /** 
     * View data informasi
     * @param String id_penerimaan
     * @return View
     */
    public function dataInformasi(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $data_informasi = InformasiPpdb::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->first();

        $isi_informasi = '';
        if ($data_informasi != null) $isi_informasi = $data_informasi->isi_informasi;

        return view('ppdb/pendaftaran/data-informasi/data-informasi', compact('auth_data', 'isi_informasi'));
    }

    /** 
     * Action post data informasi
     * @param String isi_informasi
     * @return Code 300 fail, 204 success
     */
    public function actionPostDataInformasi(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'isi_informasi' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now();

            /** find data informasi */
            $data_informasi = InformasiPpdb::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->first();

            if ($data_informasi == null) { // CREATE INFORMASI DATA
                /** generate id_data_informasi */
                $id_data_informasi = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                /** create data informasi */
                $data_informasi                     = new InformasiPpdb;
                $data_informasi->id_informasi_ppdb  = $id_data_informasi;
                $data_informasi->id_pengguna_input  = $input->auth_data->pengguna->id_pengguna;
                $data_informasi->isi_informasi      = $input->isi_informasi;
                $data_informasi->is_aktif           = 1;
                $data_informasi->id_sekolah         = $input->auth_data->sekolah_data->id_sekolah;
                $data_informasi->created_by         = $input->auth_data->pengguna->id_pengguna;
                $data_informasi->save();
            } else { // UPDATE
                /** update data informasi */
                $data_informasi->isi_informasi      = $input->isi_informasi;
                $data_informasi->is_aktif           = 1;
                $data_informasi->updated_at         = $now;
                $data_informasi->updated_by         = $input->auth_data->pengguna->id_pengguna;
                $data_informasi->save();
            }

            return [
                'status'    => 202, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/data-informasi',
                'message'   => 'Data Informasi Saved Successfully'
            ];
        }
    }
}
