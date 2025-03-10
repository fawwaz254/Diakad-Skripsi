<?php

namespace App\Http\Controllers\Guru\FormGuru;

use App\Http\Controllers\Controller;
use App\Models\DetailJawabanForm;
use App\Models\Form;
use App\Models\JawabanForm;
use App\Models\PertanyaanForm;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FormHarianController extends Controller
{
    public function viewInputFormHarian(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('guru/form-guru/input-form-harian/view-input-form-harian', compact('auth_data'));
    }

    public function datatablesInputFormHarian(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $id_pengguna = $auth_data->pengguna->id_pengguna;
        $list_form = Form::with(['jawaban_form' => function ($q) use ($id_pengguna) {
            $q->where('created_by', $id_pengguna)->orderBy('created_at', 'desc');
        }])->where('id_role', '2')->where('is_aktif', '1');

        return Datatables::of($list_form)
            ->addColumn('time', function ($item) {
                return Carbon::parse($item->start_time)->format('H:i') . ' - ' . Carbon::parse($item->end_time)->format('H:i');
            })
            ->addColumn('jumlah_jawaban', function ($item) {
                return $item->jawaban_form->count();
            })
            ->addColumn('last_data', function ($item) {
                if ($item->jawaban_form->count() > '0') {
                    return Carbon::parse($item->jawaban_form->first()->updated_at)->diffForHumans();
                } else {
                    return '';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_form
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesJawaban(Request $request, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $id_pengguna = $auth_data->pengguna->id_pengguna;
        $list_form = JawabanForm::where('created_by', $id_pengguna)->where('id_form', $id_form);

        return Datatables::of($list_form)
            ->addColumn('time', function ($item) {
                if ($item->count() > '0') {
                    return Carbon::parse($item->created_at);
                } else {
                    return '';
                }
            })
            ->addColumn('last_update', function ($item) {
                if ($item->count() > '0') {
                    return Carbon::parse($item->updated_at)->diffForHumans();
                } else {
                    return '';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jawaban_form
                );
                return $data;
            })
            ->make(true);
    }

    public function addInputFormHarian(Request $request, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $form = Form::with('pertanyaan_form')->find($id_form);
        // $before = DetailJawabanForm::where('created_by',$auth_data->pengguna->id_pengguna)->where('id_jawaban_form',$beforeJawaban->id_jawaban_form)->get();
        // if($before){
        //     return view('guru/form-guru/input-form-harian/add-input-form-harian', compact('auth_data', 'form','before'));
        // }
        return view('guru/form-guru/input-form-harian/add-input-form-harian', compact('auth_data', 'form'));
    }

    public function actionInputFormHarian(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        switch ($mode) {
            case 'add':
                $syarat = [
                    'jawaban_pertanyaan'  => 'required',
                ];
                break;
            case 'edit':
                $syarat = [
                    'jawaban_pertanyaan'  => 'required',
                ];
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
            $now = Carbon::now()->toTimeString();

            $form = Form::where('id_form', $request->id_form)->first();

            $start = $form->start_time;
            $end = $form->end_time;

            if (!(($start <= $end && ($now >= $start && $now <= $end)) || ($start >= $end && ($now >= $start || $now <= $end))) && $mode !== 'delete') {
                return [
                    'status' => 300, // FAILED
                    'message' => 'Anda mengisi di luar waktu yang ditentukan.'
                ];
            }

            if ($mode == 'add') {

                $jawaban_form = new JawabanForm;
                $jawaban_form->id_jawaban_form =  auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                $jawaban_form->id_form          = $input->id_form;
                $jawaban_form->created_by = $auth_data->pengguna->id_pengguna;
                $jawaban_form->save();

                foreach ($input->id_pertanyaan_form as $key => $value) {
                    $detail_jawaban_form = new DetailJawabanForm;
                    $detail_jawaban_form->id_detail_jawaban_form = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();;
                    $detail_jawaban_form->id_jawaban_form = $jawaban_form->id_jawaban_form;
                    $detail_jawaban_form->id_pertanyaan_form = $input->id_pertanyaan_form[$key];
                    if ($input->jenis_pertanyaan[$key] == '1') {
                        $detail_jawaban_form->jawaban = $input->jawaban_pertanyaan[$key];
                    } elseif ($input->jenis_pertanyaan[$key] == '2') {
                        $singkat_sekolah = $auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $id, request()->jawaban_pertanyaan[$key], 'public');
                        $detail_jawaban_form->jawaban = $file;
                    } elseif ($input->jenis_pertanyaan[$key] == '3') {
                        $detail_jawaban_form->jawaban = $input->jawaban_pertanyaan[$key];
                    } elseif ($input->jenis_pertanyaan[$key] == '4') { // jenis pertanyaan banyak opsi
                        // handle jawaban
                        $jawaban = [];
                        foreach ($input->jawaban_pertanyaan[$key] as $value) {
                            $jawaban[] = $value;
                        }
                        $detail_jawaban_form->jawaban = json_encode($jawaban);

                        // handle jawaban lainnya
                        if (isset($input->jawaban_lainnya[$key])) {
                            $detail_jawaban_form->jawaban_lainnya = $input->jawaban_lainnya[$key];
                        } else {
                            $detail_jawaban_form->jawaban_lainnya = null;
                        }
                    }
                    $detail_jawaban_form->created_by = $auth_data->pengguna->id_pengguna;
                    $detail_jawaban_form->save();
                }


                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-guru/input-form-harian/',
                    'message' => 'Save Successfully'
                ];
            } else if ($mode == 'edit') {

                foreach ($input->id_pertanyaan_form as $key => $value) {
                    $detail_jawaban_form = DetailJawabanForm::find($input->id_pertanyaan_form[$key][1]);
                    $detail_jawaban_form->id_jawaban_form = $input->id_jawaban_form;
                    $detail_jawaban_form->id_pertanyaan_form = $input->id_pertanyaan_form[$key][0];
                    if ($input->jenis_pertanyaan[$key] == '1') {
                        $detail_jawaban_form->jawaban = $input->jawaban_pertanyaan[$key];
                    } elseif ($input->jenis_pertanyaan[$key] == '2') {
                        if (!is_null(request()->jawaban_pertanyaan[$key]) && isset(request()->jawaban_pertanyaan[$key])) {
                            $singkat_sekolah = $auth_data->sekolah_data->nm_singkat_sekolah;
                            $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $id, request()->jawaban_pertanyaan[$key], 'public');
                            $detail_jawaban_form->jawaban = $file;
                        }
                    } elseif ($input->jenis_pertanyaan[$key] == '3') {
                        $detail_jawaban_form->jawaban = $input->jawaban_pertanyaan[$key];
                    } elseif ($input->jenis_pertanyaan[$key] == '4') { // jenis pertanyaan banyak opsi
                        // handle jawaban
                        $jawaban = [];
                        foreach ($input->jawaban_pertanyaan[$key] as $value) {
                            $jawaban[] = $value;
                        }
                        $detail_jawaban_form->jawaban = json_encode($jawaban);

                        // handle jawaban lainnya
                        if (isset($input->jawaban_lainnya[$key])) {
                            $detail_jawaban_form->jawaban_lainnya = $input->jawaban_lainnya[$key];
                        } else {
                            $detail_jawaban_form->jawaban_lainnya = null;
                        }
                    }

                    $jawaban_form =  JawabanForm::find($input->id_jawaban_form);
                    $jawaban_form->updated_by = $auth_data->pengguna->id_pengguna;
                    $jawaban_form->save();
                    $detail_jawaban_form->updated_by = $auth_data->pengguna->id_pengguna;
                    $detail_jawaban_form->save();
                }


                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-guru/input-form-harian',
                    'message' => 'Edit Successfully'
                ];
            } else if ($mode == 'delete') {
                $detail_jawaban_form = DetailJawabanForm::where('id_jawaban_form', $input->id_jawaban)
                    ->delete();
                $jawaban_form = JawabanForm::where('id_jawaban_form', $input->id_jawaban)
                    ->delete();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-guru/input-form-harian',
                    'message' => 'Delete Successfully'
                ];
            }
        }
    }

    public function viewAllSubmittedForm(Request $req, $id_form)
    {

        $input = (object) $req->input();
        $auth_data = auth_data();

        $beforeJawaban = JawabanForm::where('created_by', $auth_data->pengguna->id_pengguna)->where('id_form', $id_form)->get();
        $form = Form::where('id_form', $id_form)->first();


        return view('guru/form-guru/input-form-harian/all-input-form-harian', compact('auth_data', 'beforeJawaban', 'form'));
    }

    public function editSubmittedForm(Request $request, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $jawaban = JawabanForm::with('detail_jawaban_form')->where('created_by', $auth_data->pengguna->id_pengguna)->find($id_form);
        $form = Form::with('pertanyaan_form')->find($jawaban->id_form);
        // dd($jawaban->detail_jawaban_form->where('id_pertanyaan_form','B9hY71704268019659510f3f1bd9')->first()->jawaban);
        // DebugBar::info($form);
        return view('guru/form-guru/input-form-harian/edit-input-form-harian', compact('auth_data', 'form', 'jawaban'));
    }
}
