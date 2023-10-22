<?php

namespace App\Http\Controllers\Siswa\FormSiswa;

use App\Http\Controllers\Controller;
use App\Models\DetailJawabanForm;
use App\Models\Form;
use App\Models\JawabanForm;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Validator;

class InputFormHarianController extends Controller
{
    public function viewInputFormHarian(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('siswa/form-siswa/input-form-harian/view-input-form-harian', compact('auth_data'));
    }

    public function datatablesInputFormHarian(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_form = Form::where('id_role', '3')->where('is_harian', '1');
        return Datatables::of($list_form)
            ->addColumn('time', function ($item) {
                return Carbon::parse($item->start_time)->format('H:i') . ' - ' . Carbon::parse($item->end_time)->format('H:i');
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_form
                );
                return $data;
            })
            // ->editColumn('is_harian', function ($item) {
            //     return $item->is_harian == '1' ? 'Harian' : 'Bebas';
            // })
            // ->editColumn('is_aktif', function ($item) {
            //     return $item->is_aktif == '1' ? 'Aktif' : 'Tidak Aktif';
            // })
            // ->addColumn('time', function ($item) {
            //     return Carbon::parse($item->start_time)->format('H:i') . ' - ' . Carbon::parse($item->end_time)->format('H:i');
            // })->addColumn('jumlah_pertanyaaan', function ($item) {
            //     return $item->pertanyaan_form->count();
            // })
            ->make(true);
    }

    public function addInputFormHarian(Request $request, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $form = Form::with('pertanyaan_form')->find($id_form);
        return view('siswa/form-siswa/input-form-harian/add-input-form-harian', compact('auth_data', 'form'));
    }

    public function actionInputFormHarian(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        switch ($mode) {
            case 'add':
                $syarat = [
                    'jawaban_pertanyaan'  => 'required',
                ];
                break;
            case 'edit':
                $syarat = [];
                break;
            case 'delete':
                $syarat = [];
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
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {

                $jawaban_form = new JawabanForm;
                $jawaban_form->id_jawaban_form =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $jawaban_form->id_form          = $input->id_form;
                $jawaban_form->created_by = $auth_data->pengguna->id_pengguna;
                $jawaban_form->save();

                foreach ($input->id_pertanyaan_form as $key => $value) {
                    $detail_jawaban_form = new DetailJawabanForm;
                    $detail_jawaban_form->id_detail_jawaban_form = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();;
                    $detail_jawaban_form->id_jawaban_form = $jawaban_form->id_jawaban_form;
                    $detail_jawaban_form->id_pertanyaan_form = $input->id_pertanyaan_form[$key];
                    if ($input->jenis_pertanyaan[$key] == '1') {
                        $detail_jawaban_form->jawaban = $input->jawaban[$key];
                    } elseif ($input->jenis_pertanyaan[$key] == '2') {
                        $singkat_sekolah = $auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $id, request()->jawaban_pertanyaan[$key], 'public');
                        $detail_jawaban_form->jawaban = $file;
                    } elseif ($input->jenis_pertanyaan[$key] == '3') { } elseif ($input->jenis_pertanyaan[$key] == '4') { }
                    $detail_jawaban_form->created_by = $auth_data->sekolah_data->nm_singkat_sekolah;
                    $detail_jawaban_form->save();
                }


                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-siswa/input-form-harian',
                    'message' => 'Save Successfully'
                ];
            }
        }
    }
}
