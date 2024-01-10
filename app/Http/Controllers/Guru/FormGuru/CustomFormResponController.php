<?php

namespace App\Http\Controllers\Guru\FormGuru;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\Role;
use Illuminate\Http\Request;

class CustomFormResponController extends Controller
{
    public function index(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $roles = Role::all();
        $form = CustomForm::with('form_komponen','role')->get();
        // dd($form);

        return view('guru/form-guru/custom-form/add-respon-custom-form', compact('auth_data','form','roles'));
    }
}
