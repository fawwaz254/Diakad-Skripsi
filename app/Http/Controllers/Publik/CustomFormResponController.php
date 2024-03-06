<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormKomponen;
use App\Models\CustomFormRespon;
use App\Models\CustomFormSheet;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\Role;
use App\Models\Sekolah;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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
            $form_settings = json_decode($f->form_settings);
            if (isset($form_settings['kode']) && $form_settings['kode'] == strtoupper($request->kode)) {
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

            $form_settings = json_decode($form->form_settings);
            if (isset($form_settings['limit']) && $form_settings['limit'] == 'true') {
                $sheets = CustomFormSheet::where('id_custom_form',$id)->where('created_by', $request->ip())->first();
                if ($sheets) {
                    $pesan = 'Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon';
                    return view('public/forms/index', compact('form', 'pesan'));
                }
            }

            $kelas = Kelas::where('is_aktif',1)->get();

            return view('public/forms/index', compact('form','kelas'));
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
            // dd(strtotime($now));

            $request->validate([
                'id_custom_form' => 'required',
                'respon' => 'required'
            ]);

            $form = CustomForm::findOrFail($input->id_custom_form);

            // Biar aman ~
            $form_settings = json_decode($form->form->form_settings);
            if (isset($form_settings['limit']) && $form_settings['limit'] == 'true') {
                $sheets = CustomFormSheet::where('created_by', $request->ip())->first();
                if ($sheets) {
                    $pesan = 'Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon';
                    return view('public/forms/index', compact('form', 'pesan'));
                }
            }
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->start_time);
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->end_time);

            $time_now = $now->toTimeString();

            $start= $startTime->toTimeString();
            $end = $endTime->toTimeString();

            if (
                ($form->jenis_custom_form === 'harian' && 
                !(($start <= $end && ($time_now >= $start && $time_now <= $end)) || ($start >= $end && ($time_now >= $start || $time_now <= $end)))
                ) ||
                ($form->jenis_custom_form === 'bulanan' && $now->format('d') !== $startTime->format('d') && !($now->format('d') >= $now->endOfMonth()->format('d') && $now->format('d') <= $startTime->format('d'))) ||
                ($form->jenis_custom_form !== 'harian' && $form->jenis_custom_form !== 'bulanan' && !$now->between($startTime, $endTime)) || ($form->is_aktif == 0)
            ) {
                $pesan = 'Gagal, Anda Mengisi Diluar Waktu Yang Ditentukan!';
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
                    $komponen = CustomFormKomponen::where('id_custom_form_komponen', $key)->first();
                    $tipe = $komponen['tipe_custom_form_komponen'];
                    
                    if ($tipe == 'custom_ttd' && isset($value[0])) {
                        $image_parts = explode(";base64,", $value[0]);

                        $image_type_aux = explode("image/", $image_parts[0]);

                        $image_type = $image_type_aux[1];

                        $image_base64 = base64_decode($image_parts[1]);

                        // DebugBar::info($image_base64);
                        $singkat_sekolah = $sekola->nm_singkat_sekolah;
                        $path = 'custom-form/' . $res->id_custom_form_sheet . '/';
                        $nama_file = $res->id_custom_form_respon . uniqid() . '.' . $image_type;
                        $file = Storage::disk('spaces')->put($singkat_sekolah . '/humas/' . $path . $nama_file, $image_base64, 'public');
                        $res->respon = json_encode($singkat_sekolah . '/humas/' .$path . $nama_file);
                    }else if (($request->file('respon') != null) && ($tipe == 'file_single' || $tipe == 'file_multiple' )) {
                        $singkat_sekolah = $sekola->nm_singkat_sekolah;
                        $path = 'custom-form/' . $res->id_custom_form_sheet;
                        $nama_file_base = $res->id_custom_form_respon . uniqid();
                        if ($tipe == 'file_single') {
                            if('.'.$request->file('respon')[$key][0]->getClientOriginalExtension() != $komponen->komponen_settings['jenis_file'][0]){
                                throw new ValidationException('TIPE FILE SALAH');
                            }
                            $nama_file = $nama_file_base;
                            $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $path, $request->file('respon')[$key][0], 'public');
                            $res->respon = json_encode([$file]);
                            
                        } else {
                            $multi = [];
                    
                            foreach ($request->file('respon')[$key] as $v) {
                                if(!in_array('.'.$v->getClientOriginalExtension(), $komponen->komponen_settings['jenis_file'])){
                                    throw new ValidationException('TIPE FILE SALAH');
                                }
                                $nama_file = $nama_file_base . uniqid();
                                $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/humas/' . $path, $v, 'public');
                                array_push($multi, $file);
                            }
                            $res->respon = json_encode($multi);
                        }

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
        } 
        catch (ValidationException $e){
            $pesan = $e->getMessage();

            return view('public/forms/index', compact('pesan'));
        }
        catch (Exception $e) {
            $pesan = 'Anda Tidak Ada Akses Mohon Maaf >:(';

            return view('public/forms/index', compact('pesan'));
        }
    }

    public function getDataSiswa(Request $request){

        try {

            $input = (object) $request->input();

            $request->validate([
                'id_kelas' => 'required'
            ], [
            'id_kelas.required' => 'The id_kelas field is required.'
            ]);

            $id_kelas = $input->id_kelas;

            $siswa = Pengguna::select('nm_pengguna')->whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            })->orderBy('nm_pengguna')->get();

            return response()->json(['data' => $siswa],200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 300,
                'message' => 'Data Tidak Ditemukan!'
            ],300);
        }
    }
}
