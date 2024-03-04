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
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class CustomFormController extends Controller
{

    public function customFormCodeGenerator()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        if (CustomForm::where('form_settings', 'like', '%'.$code.'%')->exists()) {
            $this->customFormCodeGenerator();
        }

        return $code;
    }

    public function index(Request $request)
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
                return $item->id_role == 99 ? 'PUBLIC' : $item->role->nm_role;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form
                );
                return $data;
            })
            ->addColumn('kode',function($item){
                $form_settings = json_decode($item->form_settings);
                return [
                    'ada' => isset($form_settings['kode']),
                    'kode' => $form_settings['kode'] ?? ''
                ];
            })
            ->make(true);
    }




    public function create(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $roles = Role::all();

        return view('humas/form-builder/custom-form/add-custom-form-bulk', compact('auth_data', 'roles'));
    }

    public function store(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        try {

            $request->validate(
                [
                    'id_role'    => 'required',
                    'nm_custom_form'  => 'required',
                    'komponen' => 'required',
                    'is_aktif'   => 'required',
                    'start_time'   => 'required',
                    'end_time'   => 'required',
                ]
            );

            DB::transaction(function () use ($request, $input, $auth_data, $now) {

                $rule = [
                    'limit' => isset($input->multiple) ? 'true' : 'false',
                    'random' => isset($input->random) ? 'true' : 'false',
                    'editable' => isset($input->editable) ? 'true' : 'false'
                ];

                if ($input->jenis_custom_form == 'harian') {
                    $input->start_time = Carbon::createfromFormat('Y-m-d H:i:s', $now->toDateString() . ' ' . $input->start_time . ':00');
                    $input->end_time = Carbon::createfromFormat('Y-m-d H:i:s', $now->toDateString() . ' ' . $input->end_time . ':00');
                } else if ($input->jenis_custom_form == 'bulanan') {

                    $input->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y') . '-1-' . $input->end_time . ' 00:00:00');
                    $input->start_time = $input->end_time;
                }
                if ($input->id_role == 99) {
                    $rule['kode'] = $this->customFormCodeGenerator();
                }


                $id_custom_form = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $custom_form = new CustomForm();
                $custom_form->id_custom_form    = $id_custom_form;
                $custom_form->id_role           = $input->id_role;
                $custom_form->nm_custom_form    = $input->nm_custom_form;
                $custom_form->jenis_custom_form = $input->jenis_custom_form;
                $custom_form->is_aktif          = $input->is_aktif;
                $custom_form->start_time        = $input->start_time;
                $custom_form->end_time          = $input->end_time;
                $custom_form->form_settings     = json_encode($rule);
                $custom_form->created_by        = $auth_data->pengguna->id_pengguna;
                $custom_form->save();

                $data_komponen = $input->komponen;

                // nambah data & pointer ke Variabel
                foreach ($data_komponen as $key => &$d) {
                    $kom_rules = [
                        'mandatory' => isset($d['mandatory']) ? 'true' : 'false'
                    ];

                    if(isset($d['jenis_file'])){
                        $kom_rules= [   
                            'mandatory' => isset($d['mandatory']) ? 'true' : 'false',
                            'jenis_file' => $d['jenis_file']
                        ];
                        unset($d['jenis_file']);
                    }
                    $id_custom_komponen = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $d['id_custom_form'] = $custom_form->id_custom_form;
                    $d['id_custom_form_komponen'] = $id_custom_komponen;
                    $d['nm_custom_form_komponen'] = $d['tipe_custom_form_komponen'] . "_" . $custom_form->nm_custom_form;
                    $d['tipe_custom_form_komponen'] = $d['tipe_custom_form_komponen'] == "null" ? 'text' : $d['tipe_custom_form_komponen'];
                    $d['option_custom_form_komponen'] = json_encode($d['option_custom_form_komponen'] ?? null);
                    $d['komponen_settings'] = json_encode($kom_rules);
                    $d['created_by'] = $auth_data->pengguna->id_pengguna;
                    $d['created_at'] = $now;

                    $d['updated_at'] = $d['created_at']->addSeconds($key + 60); // Add 60 seconds plus $key

                    unset($d['order']);
                    unset($d['mandatory']);
                    CustomFormKomponen::insert($d);
                }
            });

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'form-builder/custom-form/',
                'message' => 'Add Data Succesfully'
            ];
        } catch (Exception $e) {
            return [
                'status' => 300, // FAILED
                'message' => 'Gagal Membuat Form',
            ];
        }
    }


    public function show(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $form = CustomForm::with(['form_komponen' => function ($query) {
                $query->orderBy('updated_at', 'asc');
            }])
                ->findOrFail($id);
            $roles = Role::all();


            return view('humas/form-builder/custom-form/edit-custom-form', compact('auth_data', 'roles', 'form'));
        } catch (Exception $e) {
            $pesan = "FORM TIDAK DITEMUKAN";
            return view('humas/form-builder/custom-form/edit-custom-form', compact('auth_data', 'pesan'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $request->validate(
                [
                    'id_role'    => 'required',
                    'nm_custom_form'  => 'required',
                    'komponen' => 'required',
                    'is_aktif'   => 'required',
                    'start_time'   => 'required',
                    'end_time'   => 'required',
                ]
            );

            DB::transaction(function () use ($request, $input, $auth_data, $now, $id) {

                $rule = [
                    'limit' => isset($input->multiple) ? 'true' : 'false',
                    'random' => isset($input->random) ? 'true' : 'false',
                    'editable' => isset($input->editable) ? 'true' : 'false'
                ];
                
                if ($input->jenis_custom_form == 'harian') {
                    $input->start_time = Carbon::createfromFormat('Y-m-d H:i:s', $now->toDateString() . ' ' . $input->start_time . ':00');
                    $input->end_time = Carbon::createfromFormat('Y-m-d H:i:s', $now->toDateString() . ' ' . $input->end_time . ':00');
                } else if ($input->jenis_custom_form == 'bulanan') {

                    $input->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y') . '-1-' . $input->end_time . ' 00:00:00');
                    $input->start_time = $input->end_time;
                }

                if ($input->id_role == 99) {
                    $rule['kode'] = $this->customFormCodeGenerator();
                }


                $custom_form = CustomForm::with('form_komponen')->findOrFail($id);
                $custom_form->id_role           = $input->id_role;
                $custom_form->nm_custom_form    = $input->nm_custom_form;
                $custom_form->is_aktif          = $input->is_aktif;
                $custom_form->jenis_custom_form = $input->jenis_custom_form;
                $custom_form->start_time        = $input->start_time;
                $custom_form->end_time          = $input->end_time;
                $custom_form->form_settings     = json_encode($rule);
                $custom_form->created_by        = $auth_data->pengguna->id_pengguna;
                $custom_form->save();

                //drop semua komponen


                $data_komponen = $input->komponen;
                $last_komponen = $custom_form->form_komponen->pluck('id_custom_form_komponen')->all();

                // nambah data & pointer ke Variabel
                foreach ($data_komponen as $key => &$d) {

                    $kom_rules = [
                        'mandatory' => isset($d['mandatory']) ? 'true' : 'false'
                    ];
                    $d['id_custom_form'] = $custom_form->id_custom_form;
                    $d['nm_custom_form_komponen'] = $d['tipe_custom_form_komponen'] . "_" . $custom_form->nm_custom_form;
                    $d['tipe_custom_form_komponen'] = $d['tipe_custom_form_komponen'] == "null" ? 'text' : $d['tipe_custom_form_komponen'];
                    $d['option_custom_form_komponen'] = json_encode($d['option_custom_form_komponen'] ?? null);
                    $d['created_by'] = $auth_data->pengguna->id_pengguna;

                    $d['updated_at'] = $now->addSeconds($key + 60); // Add 60 seconds plus $key

                    unset($d['order']);
                    unset($d['mandatory']);
                    if (isset($d['id_custom_form_komponen'])) {
                        $d['komponen_settings'] = $kom_rules;

                        $id_custom_form_komponen = $d['id_custom_form_komponen'];
                        CustomFormKomponen::find($id_custom_form_komponen)->update($d);
                        unset($d['id_custom_form_komponen']);
                        $last_komponen = array_diff($last_komponen, [$id_custom_form_komponen]);
                    } else {
                        $d['created_at'] = $now;

                        $d['komponen_settings'] = json_encode($kom_rules);

                        $id_custom_komponen = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $d['id_custom_form_komponen'] = $id_custom_komponen;
                        CustomFormKomponen::insert($d);
                    }
                }

                $komponen_dihapus = CustomFormKomponen::with('form_respon')->whereIn('id_custom_form_komponen', $last_komponen)->get();

                foreach ($komponen_dihapus as $komponen) {
                    if (method_exists($komponen, 'form_respon')) {
                        $komponen->form_respon()->delete();
                    }
                }

                CustomFormKomponen::whereIn('id_custom_form_komponen', $last_komponen)->forceDelete();
            });

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'form-builder/custom-form/',
                'message' => 'Update FORM Succesfully'
            ];
        } catch (Exception $e) {
            return [
                'status' => 300, // FAILED
                'message' => $e->getMessage(),
            ];
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;

            $record = CustomForm::with('form_komponen.form_respon')->findOrFail($id);
            $record->form_komponen->each(function ($komponen) {
                if ($komponen->form_respon()->exists()) {
                    $komponen->form_respon()->delete();
                }
            });
            $record->form_komponen()->delete();
            $record->delete();

            return [
                'status' => 200,
                'message' => 'Data Berhasil Dihapus'
            ];
        } catch (Exception $e) {
            dd($e);
            return [
                'status' => 300,
                'message' => 'Tidak Dapat Menghapus Data!'
            ];
        }
    }
}
