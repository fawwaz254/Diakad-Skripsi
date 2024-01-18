<?php

namespace App\Http\Controllers\Humas\FormBuilder;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomFormRekapController extends Controller
{
    public function index(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas\form-builder\rekap-custom-form\view-list-rekap-custom-form', compact('auth_data'));
    }

    public function indexDataTables(Request $request)
    {
        $list_data = CustomForm::with('role', 'form_komponen.form_respon')->get();

        return DataTables::of($list_data)
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
}
