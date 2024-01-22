<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormKomponen;
use App\Models\CustomFormRespon;
use App\Models\CustomFormSheet;
use App\Models\Role;
use App\Models\Sekolah;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CustomFormResponController extends Controller
{
    public function landingPage(Request $request)
    {
        return view('public/forms/landing-page');
    }

    public function findForms(Request $request)
    {
        $request->validate([
            'kode' => 'required'
        ]);

        // dd($request->kode);
        $all_form = CustomForm::where('id_role', 99)->get();

        foreach ($all_form as $f) {
            if (isset($f->form_settings['kode']) && $f->form_settings['kode'] == strtoupper($request->kode)) {
                $url = base64_encode(Crypt::encrypt($f->id_custom_form));
            }
        }
        if (isset($url)) {

            return redirect('/forms/' . $url . '/viewform');
        }
        $error = 'Kode Salah';
        return view('public/forms/landing-page', compact('error'));
    }

    public function index(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $id = Crypt::decrypt(base64_decode($id));

            $form = CustomForm::with('form_komponen', 'role')->where('id_role', '99')->findOrFail($id);
            if (!$now->between($form->start_time, $form->end_time)) {
                $pesan = 'Form, Telah Dikunci Oleh Pemilik (Melewati Batas Pengumpulan).';
                return view('public/forms/index', compact('form', 'pesan'));
            }

            if (isset($form->form_settings['limit']) && $form->form_settings['limit'] == 'true') {
                $sheets = CustomFormSheet::where('created_by', $request->ip())->first();
                if ($sheets) {
                    $pesan = 'Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon';
                    return view('public/forms/index', compact('form', 'pesan'));
                }
            }
            return view('public/forms/index', compact('form'));
        } catch (Exception $e) {
            $pesan = 'Anda Tidak Ada Akses Mohon Maaf >:(';
            return view('public/forms/index', compact('pesan'));
        }
    }


    public function store(Request $request)
    {
        try {


            $input = (object) $request->input();
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $sekola = Sekolah::first();
            // dd($input);
            // dd(strtotime($now));

            $request->validate([
                'id_custom_form' => 'required',
                'respon' => 'required'
            ]);

            $form = CustomForm::findOrFail($input->id_custom_form);

            // Biar aman ~
            if (isset($form->form_settings['limit']) && $form->form_settings['limit'] == 'true') {
                $sheets = CustomFormSheet::where('created_by', $request->ip())->first();
                if ($sheets) {
                    $pesan = 'Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon';
                    return view('public/forms/index', compact('form', 'pesan'));
                }
            }

            if (!$now->between($form->start_time, $form->end_time)) {

                $pesan = 'Gagal, Anda Mengisi Diluar Jam Yang Ditentukan!';
                return view('public/forms/index', compact('form', 'pesan'));
            }
            DB::transaction(function () use ($request, $now, $sekola, $input, $form) {

                $sheet = new CustomFormSheet();
                $sheet->id_custom_form_sheet = $sekola->prefix . strtotime($now) . uniqid();
                $sheet->id_custom_form = $form->id_custom_form;
                $sheet->created_at = $now;
                $sheet->created_by = $request->ip();
                $sheet->save();

                foreach ($input->respon as $key => $value) {
                    $sekarang = Carbon::now(env('APP_TIMEZONE', ''));
                    $res = new CustomFormRespon();
                    $res->id_custom_form_respon = $sekola->prefix . strtotime($sekarang) . uniqid();
                    $res->id_custom_form_sheet = $sheet->id_custom_form_sheet;
                    $res->id_custom_form_komponen = $key;
                    $tipe = CustomFormKomponen::where('id_custom_form_komponen', $key)->select('tipe_custom_form_komponen')->first();

                    if ($tipe['tipe_custom_form_komponen'] == 'custom_ttd' && isset($value[0])) {
                        $image_parts = explode(";base64,", $value[0]);

                        $image_type_aux = explode("image/", $image_parts[0]);

                        $image_type = $image_type_aux[1];

                        $image_base64 = base64_decode($image_parts[1]);

                        // DebugBar::info($image_base64);
                        $singkat_sekolah = $sekola->nm_singkat_sekolah;
                        $path = 'custom-form/' . $res->id_custom_form_sheet . '/';
                        $nama_file = $res->id_custom_form_respon . uniqid() . '.' . $image_type;
                        $file = Storage::disk('local')->put($singkat_sekolah . '/humas/' . $path . $nama_file, $image_base64, 'public');
                        $res->respon = $path . $nama_file;
                    } else {
                        $res->respon = json_encode($value);
                    }

                    $res->created_at = $sekarang;
                    $res->created_by = $request->ip();
                    $res->save();
                }
            });

            $pesan = 'Berhasil Mengirim Respon';
            return view('public/forms/index', compact('form', 'pesan'));
        } catch (Exception $e) {
            $pesan = 'Anda Tidak Ada Akses Mohon Maaf >:(';
            return view('public/forms/index', compact('pesan'));
        }
    }
}
