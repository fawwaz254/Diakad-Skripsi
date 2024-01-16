<?php

namespace App\Http\Controllers\Guru\FormGuru;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormRespon;
use App\Models\CustomFormSheet;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomFormResponController extends Controller
{
    public function indexDataTables(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($auth_data->pengguna->role_pengguna->role->id_role);
        $list_data = CustomForm::with('role', 'form_komponen.form_respon')
            ->whereIn('id_role', $auth_data->pengguna->role_pengguna->pluck('id_role'))
            ->get();
        return DataTables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form
                );
                return $data;
            })
            ->addColumn('history', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form
                );
                return $data;
            })
            ->make(true);
    }

    public function indexAllDataTables(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = CustomFormSheet::with('form')
            ->whereHas('form', function ($query) use ($auth_data) {
                $query->whereIn('id_role', $auth_data->pengguna->role_pengguna->pluck('id_role'));
            })
            ->where('id_custom_form', $id)
            ->get();

        return DataTables::of($list_data)
            ->addColumn('last_update', function ($item) {
                return Carbon::parse($item->created_at)->diffForHumans();
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form_sheet
                );
                return $data;
            })
            ->addColumn('history', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form_sheet
                );
                return $data;
            })
            ->make(true);
    }


    public function index(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $roles = Role::all();
        $form = CustomForm::with('form_komponen', 'role')->get();

        return view('guru/form-guru/custom-form/index-respon-custom-form', compact('auth_data', 'form', 'roles'));
    }

    public function indexAllForm(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $form = CustomForm::with('form_komponen', 'role')->find($id);

        return view('guru/form-guru/custom-form/all-respon-custom-form', compact('auth_data', 'form'));
    }

    public function create(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $form = CustomForm::with('form_komponen', 'role')->find($id);

        return view('guru/form-guru/custom-form/add-respon-custom-form', compact('auth_data', 'form'));
    }

    public function store(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));



        $request->validate([
            'id_custom_form' => 'required',
            'respon' => 'required'
        ]);

        $form = CustomForm::find($input->id_custom_form);

        // Biar aman ~
        if (isset($form->form_settings['limit']) && $form->form_settings['limit'] == 'true') {
            $sheets = CustomFormSheet::where('created_by', $auth_data->pengguna->id_pengguna)->first();
            if ($sheets) {
                return [
                    'status' => 300,
                    'message' => 'Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon'
                ];
            }
        }
        if (!$now->between($form->start_time, $form->end_time)) {
            return [
                'status' => 300,
                'message' => 'Gagal, Anda Mengisi Diluar Jam Yang Ditentukan!'
            ];
        }

        $sheet = new CustomFormSheet();
        $sheet->id_custom_form_sheet = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
        $sheet->id_custom_form = $form->id_custom_form;
        $sheet->created_at = $now;
        $sheet->created_by = $auth_data->pengguna->id_pengguna;
        $sheet->save();

        foreach ($input->respon as $key => $value) {
            $sekarang = Carbon::now(env('APP_TIMEZONE', ''));
            $res = new CustomFormRespon();
            $res->id_custom_form_respon = $input->auth_data->sekolah_data->prefix . strtotime($sekarang) . uniqid();
            $res->id_custom_form_sheet = $sheet->id_custom_form_sheet;
            $res->id_custom_form_komponen = $key;
            $res->respon = json_encode($value);
            $res->created_at = $sekarang;
            $res->created_by = $auth_data->pengguna->id_pengguna;
            $res->save();
        }

        return [
            'status' => 200,
            'message' => 'Berhasil Mengirim Respon'
        ];
    }

    public function show(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $form = CustomFormSheet::with('form.form_komponen', 'form_respon.form_komponen')->find($id);



        return view('guru/form-guru/custom-form/edit-respon-custom-form', compact('auth_data', 'form'));
    }

    public function update(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $sheet = CustomFormSheet::with('form.form_komponen', 'form_respon.form_komponen')->find($id);
        if (!$now->between($sheet->form->start_time, $sheet->form->end_time) || (Carbon::today()->toDateString() != $sheet->created_at->toDateString())) {
            return [
                'status' => 300,
                'message' => 'Gagal, Form Telah Dikunci!'
            ];
        }

        foreach ($input->respon as $key => $value) {
            $sekarang = Carbon::now(env('APP_TIMEZONE', ''));
            $res = $sheet->form_respon->where('id_custom_form_komponen', $key)->first()->update([
                'respon' => json_encode($value),
                'updated_at' => $sekarang,
                'updated_by' => $auth_data->pengguna->id_pengguna
            ]);
        }

        return [
            'status' => 200,
            'message' => 'Berhasil Mengubah Data!'
        ];
    }

    public function destroy(Request $request, $id)
    {

        $record = CustomFormSheet::with('form_respon')->findOrFail($id);
        $record->form_respon()->delete();
        $record->delete();
        return [
            'status' => 200,
            'message' => 'GG'
        ];
    }
}
