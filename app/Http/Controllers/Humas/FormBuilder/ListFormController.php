<?php

namespace App\Http\Controllers\Humas\FormBuilder;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\PertanyaanForm;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;


class ListFormController extends Controller
{
    public function viewListForm(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/form-builder/list-form/view-list-form', compact('auth_data'));
    }


    public function addListForm(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $roles = Role::whereIn('id_role', [2, 3, 15])->get();

        return view('humas/form-builder/list-form/add-list-form', compact('auth_data', 'roles'));
    }

    public function editListForm(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $roles = Role::whereIn('id_role', [2, 3, 15])->get();
        $form = Form::findOrFail($id);

        return view('humas/form-builder/list-form/edit-list-form', compact('auth_data', 'roles', 'form'));
    }

    public function actionListForm(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $now = Carbon::now();
        $list_validator = [
            'id_role'    => 'required',
            'nm_form'     => 'required',
            'is_harian'   => 'required',
            'is_aktif'   => 'required',
            'start_time'   => 'required',
            'end_time'   => 'required',
        ];

        $validator = Validator::make($request->all(), $list_validator);


        if ($validator->fails() && $mode != 'delete') {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'add') {
                $id_form = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $form                               = new Form;
                $form->id_form                      = $id_form;
                $form->id_role                      = $input->id_role;
                $form->nm_form                      = $input->nm_form;
                $form->is_harian                    = $input->is_harian;
                $form->is_aktif                     = $input->is_aktif;
                $form->start_time                   = $input->start_time;
                $form->end_time                     = $input->end_time;
                $form->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $form->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/list-form',
                    'message' => 'Save Data List Form Succesfully'
                ];
            } elseif ($mode == "edit") {
                $form         = Form::findOrFail($id);
                $form->id_role                      = $input->id_role;
                $form->nm_form                      = $input->nm_form;
                $form->is_harian                    = $input->is_harian;
                $form->is_aktif                     = $input->is_aktif;
                $form->start_time                   = $input->start_time;
                $form->end_time                     = $input->end_time;
                $form->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $form->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/list-form',
                    'message' => 'Update Form Data Succesfully'
                ];
            } elseif ($mode == "delete") {
                $form = Form::with('pertanyaan_form')->findOrFail($id);
                $form->pertanyaan_form()->delete();
                $form->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Form Data Succesfully'
                ];
            }
        }
    }

    public function datatablesListForm(Request $request)
    {
        $list_data = Form::with('role', 'pertanyaan_form');
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_form
                );
                return $data;
            })
            ->editColumn('is_harian', function ($item) {
                return $item->is_harian == '1' ? 'Harian' : 'Bebas';
            })
            ->editColumn('is_aktif', function ($item) {
                return $item->is_aktif == '1' ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('time', function ($item) {
                return Carbon::parse($item->start_time)->format('H:i') . ' - ' . Carbon::parse($item->end_time)->format('H:i');
            })->addColumn('jumlah_pertanyaaan', function ($item) {
                return $item->pertanyaan_form->count();
            })
            ->make(true);
    }

    public function viewPertanyaanForm(Request $request, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('humas/form-builder/list-form/view-pertanyaan-form', compact('auth_data', 'id_form'));
    }

    public function viewAddPertanyaanForm(Request $request, $jenis_pertanyaan, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $form = Form::with('pertanyaan_form')->find($id_form);
        $urutan = $form->pertanyaan_form->count() + 1;
        if ($jenis_pertanyaan == '1' || $jenis_pertanyaan == '2') {
            return view('humas/form-builder/list-form/add-pertanyaan-form', compact('auth_data', 'id_form', 'jenis_pertanyaan', 'urutan'));
        } else {
            return view('humas/form-builder/list-form/add-pertanyaan-form-opsi', compact('auth_data', 'id_form', 'jenis_pertanyaan', 'urutan'));
        }
    }

    public function viewEditPertanyaanForm(Request $request, $jenis_pertanyaan, $id_form, $id_pertanyaan_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pertanyaan_form = PertanyaanForm::findOrFail($id_pertanyaan_form);
        $opsi = [];
        if (isset($pertanyaan_form->options)) {

            $opsi = array_combine(
                json_decode($pertanyaan_form->options),
                json_decode($pertanyaan_form->label_color) ?? json_decode($pertanyaan_form->options)
            );
        }
        $other = $pertanyaan_form->others;

        if ($jenis_pertanyaan == '1' || $jenis_pertanyaan == '2') {
            return view('humas/form-builder/list-form/edit-pertanyaan-form', compact('auth_data', 'id_form', 'jenis_pertanyaan', 'pertanyaan_form'));
        } else {
            return view('humas/form-builder/list-form/edit-pertanyaan-form-opsi', compact('auth_data', 'id_form', 'jenis_pertanyaan', 'pertanyaan_form', 'opsi', 'other'));
        }
    }

    public function datatablesPertanyaanForm(Request $request, $id_form)
    {
        $list_data = PertanyaanForm::where('id_form', $id_form);

        return Datatables::of($list_data)
            ->editColumn('options', function ($item) {
                $data_opsi = [];
                if (!empty($item->options)) {
                    $options = json_decode($item->options, true);
                    foreach ($options as $opsi) {
                        $data_opsi[] = $opsi;
                    }
                }
                return $data_opsi;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_form' => $item->id_form,
                    'id_pertanyaan_form' => $item->id_pertanyaan_form,
                    'jenis_pertanyaan' => $item->jenis_pertanyaan
                );
                return $data;
            })
            ->editColumn('jenis_pertanyaan', function ($item) {
                switch ($item->jenis_pertanyaan) {
                    case 1:
                        return 'Text';
                    case 2:
                        return 'Foto';
                    case 3:
                        return 'Satu Opsi';
                    default:
                        return 'Banyak Opsi';
                }
            })
            ->make(true);
    }

    public function actionPertanyaanForm(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $now = Carbon::now();
        $list_validator = [
            'id_form'               => 'required',
            'jenis_pertanyaan'      => 'required',
            'nm_pertanyaan_form'         => 'required',
            'urutan'                => 'required',
        ];

        $validator = Validator::make($request->all(), $list_validator);


        if ($validator->fails() && $mode != 'delete') {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'add') {
                $id_pertanyaan_form = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $pertanyaan_form                               = new PertanyaanForm;
                $pertanyaan_form->id_pertanyaan_form           = $id_pertanyaan_form;
                $pertanyaan_form->id_form                      = $input->id_form;
                $pertanyaan_form->nm_pertanyaan_form           = $input->nm_pertanyaan_form;
                $pertanyaan_form->jenis_pertanyaan             = $input->jenis_pertanyaan;
                $pertanyaan_form->urutan                       = $input->urutan;
                $pertanyaan_form->created_by                   = $input->auth_data->pengguna->id_pengguna;

                if ($input->jenis_pertanyaan == '3' || $input->jenis_pertanyaan == '4') {
                    // Handle opsi pertanyaan
                    $opsi = [];
                    foreach ($input->options as $options) {
                        $opsi[] = $options;
                    }
                    $pertanyaan_form->options                      = json_encode($opsi);

                    // Handle opsi lainnya
                    if (property_exists($input, 'others')) {
                        if ($input->others == '1') {
                            $pertanyaan_form->others = '1';
                        } else {
                            $pertanyaan_form->others = '0';
                        }
                    } else {
                        $pertanyaan_form->others = '0';
                    }

                    if ($input->jenis_pertanyaan == '3') {
                        foreach ($input->color as $color) {
                            $warna[] = $color;
                        }
                        $pertanyaan_form->label_color                  = json_encode($warna);
                    }
                } else {
                    $pertanyaan_form->label_color                  = null;
                    $pertanyaan_form->options                      = null;
                    $pertanyaan_form->others                       = '0';
                }
                $pertanyaan_form->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/list-form/pertanyaan/' . $input->id_form,
                    'message' => 'Save Data Pertanyaan Form Succesfully'
                ];
            } elseif ($mode == "edit") {
                $pertanyaan_form                               = PertanyaanForm::findOrFail($id);
                $pertanyaan_form->id_form                      = $input->id_form;
                $pertanyaan_form->nm_pertanyaan_form           = $input->nm_pertanyaan_form;
                $pertanyaan_form->jenis_pertanyaan             = $input->jenis_pertanyaan;
                $pertanyaan_form->urutan                       = $input->urutan;
                $pertanyaan_form->updated_by                   = $input->auth_data->pengguna->id_pengguna;

                if ($input->jenis_pertanyaan == '3' || $input->jenis_pertanyaan == '4') {
                    $opsi = [];
                    foreach ($input->options as $options) {
                        $opsi[] = $options;
                    }
                    $pertanyaan_form->options                      = json_encode($opsi);

                    // Handle opsi lainnya
                    if (property_exists($input, 'others')) {
                        if ($input->others == '1') {
                            $pertanyaan_form->others = '1';
                        } else {
                            $pertanyaan_form->others = '0';
                        }
                    } else {
                        $pertanyaan_form->others = '0';
                    }

                    if ($input->jenis_pertanyaan == '3') {

                        foreach ($input->color as $color) {
                            $warna[] = $color;
                        }
                        $pertanyaan_form->label_color                  = json_encode($warna);
                    }
                } else {
                    $pertanyaan_form->label_color                  = null;
                    $pertanyaan_form->options   = null;
                    $pertanyaan_form->others                       = '0';
                }
                $pertanyaan_form->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/list-form/pertanyaan/' . $input->id_form,
                    'message' => 'Update Data Pertanyaan Form Succesfully'
                ];
            } elseif ($mode == "delete") {
                $form = PertanyaanForm::findOrFail($id);
                $form->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Pertanyaan Form Succesfully'
                ];
            }
        }
    }
}
