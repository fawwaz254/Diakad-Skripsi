<?php

namespace App\Http\Controllers\Humas\BursaKerja;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\LowonganKerja;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;
use Session;
use Validator;

class LowonganKerjaController extends BaseController
{

    public function viewLowonganKerja(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/bursa-kerja/lowongan-kerja/view-lowongan-kerja', compact('auth_data'));
    }

    public function viewAddEditLowonganKerja(Request $request, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (!empty($id)) {
            $item = LowonganKerja::find($id);
        } else {
            $item = null;
        }

        return view('humas/bursa-kerja/lowongan-kerja/view-add-edit-lowongan-kerja', compact('auth_data', 'item'));
    }

    public function actionLowonganKerja(Request $request, $mode)
    {

        $input = (object) $request->input();

        switch ($mode) {
            case 'add':
                $syarat = [
                    'judul_lowongan_kerja'      => 'required',
                    'deskripsi_lowongan_kerja'  => 'required',
                ];
                break;
            case 'edit':
                $syarat = [
                    'id_lowongan_kerja'         => 'required',
                    'judul_lowongan_kerja'      => 'required',
                    'deskripsi_lowongan_kerja'  => 'required',
                ];
                break;
            case 'delete':
                $syarat = [
                    'id_lowongan_kerja'        => 'required',
                ];
                break;
            default:
                return;
        }

        $validator = Validator::make($request->all(), $syarat);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                // dd($input);
                foreach ($request->input('judul_lowongan_kerja') as $key => $value) {

                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $lowongan_kerja                             = new LowonganKerja;
                    $lowongan_kerja->id_lowongan_kerja          = $id;
                    $lowongan_kerja->judul_lowongan_kerja       = $input->judul_lowongan_kerja[$key];
                    $lowongan_kerja->deskripsi_lowongan_kerja   = isset($input->deskripsi_lowongan_kerja[$key]) ? $input->deskripsi_lowongan_kerja[$key] : '-';

                    if (!empty(request()->file)) {
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        // $image = request()->file[$key];
                        // $image->storeAs('public/photos', $image->hashName());
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $id, request()->file[$key], 'public');
                        $lowongan_kerja->poster_lowongan_kerja   = $file;
                    }

                    $lowongan_kerja->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $lowongan_kerja->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'bursa-kerja/lowongan-kerja',
                    'message' => 'Save Successfully'
                ];
            } elseif ($mode == 'edit') {
                foreach ($request->input('judul_lowongan_kerja') as $key => $value) {
                    // $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    // $lowongan_kerja                             = new LowonganKerja;
                    // $lowongan_kerja->id_lowongan_kerja          = $id;
                    $lowongan_kerja                             = LowonganKerja::find($input->id_lowongan_kerja[$key]);
                    $lowongan_kerja->judul_lowongan_kerja       = $input->judul_lowongan_kerja[$key];
                    $lowongan_kerja->deskripsi_lowongan_kerja   =  isset($input->deskripsi_lowongan_kerja[$key]) ? $input->deskripsi_lowongan_kerja[$key] : '-';

                    if (!empty(request()->file)) {
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        // $image = request()->file[$key];
                        // $image->storeAs('public/photos', $image->hashName());
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $id, request()->file[$key], 'public');
                        $lowongan_kerja->poster_lowongan_kerja   = $file;
                    }

                    $lowongan_kerja->created_by                 = $input->auth_data->pengguna->id_pengguna;
                    $lowongan_kerja->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'bursa-kerja/lowongan-kerja',
                    'message' => 'Save Successfully'
                ];
            }
        }
    }

    public function actionDeleteLowonganKerja(Request $request, $id)
    {

        $input = (object) $request->input();

        $lowongan_kerja               = LowonganKerja::findOrFail($id);
        $lowongan_kerja->deleted_by   = $input->auth_data->pengguna->id_pengguna;
        $lowongan_kerja->save();

        $lowongan_kerja->delete();

        return [
            'status' => 203, // SUCCESS AND LOAD TABLE
            'message' => 'Delete Lowongan Kerja Successfully'
        ];
    }

    public function showDatatablesLowonganKerja(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LowonganKerja::orderBy('created_at', 'desc');

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                if ($item->poster_lowongan_kerja) {
                    $poster =  Storage::disk('spaces')->url($item->poster_lowongan_kerja);
                    $ext = pathinfo($item->poster_lowongan_kerja, PATHINFO_EXTENSION);
                    if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx') {
                        $note = 'file';
                    } else {
                        $note = 'image';
                    }
                } else {
                    $poster = null;
                    $note = null;
                }

                $data = array(
                    'id' => $item->id_lowongan_kerja,
                    'poster' => $poster,
                    'note' => $note
                );
                return $data;
            })
            ->make(true);
    }
}
