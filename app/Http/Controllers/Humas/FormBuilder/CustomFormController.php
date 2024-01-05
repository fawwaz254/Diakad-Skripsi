<?php

namespace App\Http\Controllers\Humas\FormBuilder;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormKomponen;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\PertanyaanForm;
use App\Models\Role;
use Validator;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class CustomFormController extends Controller
{
    public function viewCustomForm(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/form-builder/custom-form/view-custom-form', compact('auth_data'));
    }

    public function datatablesCustomForm(Request $request)
    {
        $list_data = CustomForm::with('role', 'form_komponen.form_respon')->get();

        return Datatables::of($list_data)
            ->addColumn('nm_role', function ($item) {
                return $item->id_role == 'PUBLIC' ? 'PUBLIC' : $item->role->nm_role;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form
                );
                return $data;
            })
            ->make(true);
    }

    public function addCustomForm(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $roles = Role::whereIn('id_role', [2, 3, 15])->get();

        return view('humas/form-builder/custom-form/add-custom-form', compact('auth_data', 'roles'));
    }

    public function editCustomForm(Request $request, $id_custom_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $roles = Role::whereIn('id_role', [2, 3, 15])->get();
        $form = CustomForm::findOrFail($id_custom_form);

        return view('humas/form-builder/custom-form/edit-custom-form', compact('auth_data', 'roles', 'form'));
    }


    public function actionCustomForm(Request $request, $mode, $id_custom_form = null)
    {
        $input = (object) $request->input();
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $list_validator = [
            'id_role'    => 'required',
            'nm_custom_form'  => 'required',
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
                $id_custom_form = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $form                               = new CustomForm();
                $form->id_custom_form               = $id_custom_form;
                $form->id_role                      = $input->id_role;
                $form->nm_custom_form               = $input->nm_custom_form;
                $form->is_aktif                     = $input->is_aktif;
                $form->start_time                   = $input->start_time;
                $form->end_time                     = $input->end_time;
                $form->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $form->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/custom-form',
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($mode == "edit") {
                $form                               = CustomForm::findOrFail($id_custom_form);
                $form->id_role                      = $input->id_role;
                $form->nm_custom_form                      = $input->nm_custom_form;
                $form->is_aktif                     = $input->is_aktif;
                $form->start_time                   = $input->start_time;
                $form->end_time                     = $input->end_time;
                $form->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $form->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/custom-form',
                    'message' => 'Update Data Succesfully'
                ];
            } elseif ($mode == "delete") {
                $form = CustomForm::with('form_komponen.form_respon')->findOrFail($id_custom_form);

                foreach ($form->form_komponen()->form_respon() as $child) {
                    $child->delete();
                }
                foreach ($form->form_komponen() as $child) {
                    $child->delete();
                }
                $form->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Succesfully'
                ];
            }
        }
    }

    // KOMPONEN
    public function viewCustomFormKomponen(Request $request, $id_custom_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/form-builder/custom-form/view-custom-form-komponen', compact('auth_data', 'id_custom_form'));
    }

    public function datatablesCustomFormKomponen(Request $request, $id_custom_form)
    {
        $list_data = CustomFormKomponen::where('id_custom_form', $id_custom_form)->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form_komponen
                );
                return $data;
            })
            ->make(true);
    }

    public function addCustomFormKomponen(Request $request, $id_custom_form)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/form-builder/custom-form/add-custom-form-komponen', compact('auth_data', 'id_custom_form'));
    }

    public function editCustomFormKomponen(Request $request, $id_custom_form, $id_custom_form_komponen)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $form_komponen = CustomFormKomponen::find($id_custom_form_komponen);

        return view('humas/form-builder/custom-form/edit-custom-form-komponen', compact('auth_data', 'id_custom_form', 'form_komponen'));
    }

    public function actionCustomFormKomponen(Request $request, $mode, $id_custom_form_komponen = null)
    {
        $input = (object) $request->input();
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $list_validator = [
            'id_custom_form'    => 'required',
            'label_custom_form_komponen'  => 'required',
            'tipe_custom_form_komponen'  => 'required',
        ];

        $validator = Validator::make($request->all(), $list_validator);

        if ($validator->fails() && $mode != 'delete') {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'add') {
                $id_custom_form_komponen = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $form_komponen                               = new CustomFormKomponen();
                $form_komponen->id_custom_form_komponen      = $id_custom_form_komponen;
                $form_komponen->id_custom_form               = $input->id_custom_form;
                $form_komponen->label_custom_form_komponen   = $input->label_custom_form_komponen;
                $form_komponen->nm_custom_form_komponen      = str_replace(' ', '_', strtolower($input->label_custom_form_komponen));
                $form_komponen->tipe_custom_form_komponen    = $input->tipe_custom_form_komponen;
                $form_komponen->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $form_komponen->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/custom-form/komponen/' . $input->id_custom_form,
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($mode == "edit") {
                $form_komponen         = CustomFormKomponen::findOrFail($id_custom_form_komponen);
                $form_komponen->id_custom_form               = $input->id_custom_form;
                $form_komponen->label_custom_form_komponen   = $input->label_custom_form_komponen;
                $form_komponen->nm_custom_form_komponen      = str_replace(' ', '_', strtolower($input->label_custom_form_komponen));
                $form_komponen->tipe_custom_form_komponen    = $input->tipe_custom_form_komponen;
                $form_komponen->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $form_komponen->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'form-builder/custom-form/komponen/' . $input->id_custom_form,
                    'message' => 'Update Data Succesfully'
                ];
            } elseif ($mode == "delete") {
                $form_komponen = CustomFormKomponen::with('form_respon')->findOrFail($id_custom_form_komponen);

                foreach ($form_komponen->form_respon() as $child) {
                    $child->delete();
                }
                $form_komponen->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Form Data Succesfully'
                ];
            }
        }
    }
}
