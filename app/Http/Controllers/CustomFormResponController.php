<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormKomponen;
use App\Models\CustomFormRespon;
use App\Models\CustomFormSheet;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\Role;
use Carbon\Carbon;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class CustomFormResponController extends Controller
{
    /**
     * Method ambil modul name
     */
    public static function redirectBack($url)
    {
        $segments = explode('/', $url);
        $newUrl = $segments[1];
        return $newUrl;
    }

    /**
     * Datatables FormList
     * @method List Semua Form Yang Tersedia Untuk User Dengan ID ROLE 
     * @param Request $request => Pastikan Berisi Input Auth
     */
    public function indexDataTables(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
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

    /**
     * Datatables SheetList
     * @method List Semua SheetForm (FORM YANG SAMA Dapat Memiliki lebih dari satu Respon)
     *         Yang Tersedia Untuk User Dengan ID ROLE 
     * @param Request $request => Pastikan Berisi Input Auth
     */
    public function indexAllDataTables(Request $request, $id)
    {


        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = CustomFormSheet::with('form')
            ->whereHas('form', function ($query) use ($auth_data) {
                $query->whereIn('id_role', $auth_data->pengguna->role_pengguna->pluck('id_role'));
            })
            ->where('id_custom_form', $id)
            ->where('created_by', $auth_data->pengguna->id_pengguna)
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


    /**
     * @method view dari custom-form
     * 
     */
    public function index(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('global/custom-form/index-respon-custom-form', compact('auth_data'));
    }

    /**
     * List Dari Sheet Yang Ada dari Form
     */
    public function indexAllForm(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $form = CustomForm::with('form_komponen', 'role')->whereIn('id_role', $auth_data->pengguna->role_pengguna->pluck('id_role'))->findOrFail($id);

            return view('global/custom-form/all-respon-custom-form', compact('auth_data', 'form'));
        } catch (ModelNotFoundException $e) {
            $pesan = "Form Tidak Ditemukan";
            return view('global/custom-form/all-respon-custom-form', compact('auth_data', 'pesan'));
        }
    }

    /**
     * Membuat Sheet Baru atau Mengisi FORM
     */
    public function create(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            
            $form = CustomForm::with(['form_komponen' => function ($query) {
                $query->orderBy('updated_at', 'asc');
            }, 'role'])
                ->whereIn('id_role', $auth_data->pengguna->role_pengguna->pluck('id_role'))
                ->findOrFail($id);
            if (isset($form->form_settings['limit']) && $form->form_settings['limit'] == 'true') {
                $sheets = CustomFormSheet::where('created_by', $auth_data->pengguna->id_pengguna)->where('id_custom_form', $id)->first();
                if ($sheets) {
                    throw new Exception('Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon');
                }
            }
            if ($form->jenis_custom_form === 'harian') {
                $sheets = CustomFormSheet::where('created_by', $auth_data->pengguna->id_pengguna)->whereDate('created_at', '>=', Carbon::today()->toDateString())->where('id_custom_form', $id)->first();
                if ($sheets) {
                    throw new Exception('Anda Telah Mengisi Form ' . $form->nm_custom_form . ' Hari Ini');
                }
            } else if ($form->jenis_custom_form === 'bulanan') {
                $sheets = CustomFormSheet::where('created_by', $auth_data->pengguna->id_pengguna)->whereDay('created_at', Carbon::today()->format('d'))->where('id_custom_form', $id)->first();
                if ($sheets) {
                    throw new Exception('Anda Telah Mengisi Form ' . $form->nm_custom_form . ' Bulan Ini');
                }
            }
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->start_time);
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->end_time);


            if (
                ($form->jenis_custom_form === 'harian' && !$now->between($startTime->toTimeString(), $endTime->toTimeString())) ||
                ($form->jenis_custom_form === 'bulanan' && $now->format('d') !== $startTime->format('d') && !($now->format('d') >= $now->endOfMonth()->format('d') && $now->format('d') <= $startTime->format('d'))) ||
                ($form->jenis_custom_form !== 'harian' && $form->jenis_custom_form !== 'bulanan' && !$now->between($startTime, $endTime)) || ($form->is_aktif == 0)
            ) {
                throw new Exception('FORM TELAH DIKUNCI');
            }
            
            $hello = $form->whereHas('form_komponen',function($query){
                $query->whereIn('tipe_custom_form_komponen',['custom_kelas','custom_siswa']);
            })->find($id);
            if(!is_null($hello)){
                $kelas = Kelas::where('is_aktif', 1)->get();

                return view('global/custom-form/add-respon-custom-form', compact('auth_data', 'form','kelas'));
            }

            return view('global/custom-form/add-respon-custom-form', compact('auth_data', 'form'));
        } catch (ModelNotFoundException $e) {
            $pesan = $e->getMessage()   ;
            return view('global/custom-form/add-respon-custom-form', compact('auth_data', 'pesan'));
        } catch (Exception $e) {
            $pesan = $e->getMessage();
            return view('global/custom-form/add-respon-custom-form', compact('auth_data', 'pesan'));
        }
    }

    /**
     * Fungsi Menyimpan
     */
    public function store(Request $request)
    {
        try {
            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $now = Carbon::now(env('APP_TIMEZONE', ''));


            $request->validate([
                'id_custom_form' => 'required',
                'respon' => 'required'
            ]);

            $form = CustomForm::findOrFail($input->id_custom_form);

            // Biar aman ~
            if (isset($form->form_settings['limit']) && $form->form_settings['limit'] == 'true') {
                $sheets = CustomFormSheet::where('created_by', $auth_data->pengguna->id_pengguna)->where('id_custom_form', $form->id_custom_form)->first();
                if ($sheets) {
                    return [
                        'status' => 300,
                        'message' => 'Form ' . $form->nm_custom_form . ' Hanya Menerima 1 Respon'
                    ];
                }
            }

            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->start_time);
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->end_time);

            if (
                ($form->jenis_custom_form === 'harian' && !$now->between($startTime->toTimeString(), $endTime->toTimeString())) ||
                ($form->jenis_custom_form === 'bulanan' && $now->format('d') !== $startTime->format('d') && !($now->format('d') >= $now->endOfMonth()->format('d') && $now->format('d') <= $startTime->format('d'))) ||
                ($form->jenis_custom_form !== 'harian' && $form->jenis_custom_form !== 'bulanan' && !$now->between($startTime, $endTime)) || ($form->is_aktif == 0)
            ) {
                return [
                    'status' => 300,
                    'message' => 'Gagal, Anda Mengisi Diluar Jam Yang Ditentukan!'
                ];
            }

            DB::transaction(function () use ($request, $input, $auth_data, $form, $now) {

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
                    $komponen = CustomFormKomponen::where('id_custom_form_komponen', $key)->first();
                    $tipe = $komponen['tipe_custom_form_komponen'];
                    
                    if ($tipe == 'custom_ttd' && isset($value[0])) {
                        $image_parts = explode(";base64,", $value[0]);

                        $image_type_aux = explode("image/", $image_parts[0]);

                        $image_type = $image_type_aux[1];

                        $image_base64 = base64_decode($image_parts[1]);

                        DebugBar::info($image_base64);
                        $singkat_sekolah = $auth_data->sekolah_data->nm_singkat_sekolah;
                        $path = 'custom-form/' . $res->id_custom_form_sheet . '/';
                        $nama_file = $res->id_custom_form_respon . uniqid() . '.' . $image_type;
                        $file = Storage::disk('spaces')->put($singkat_sekolah . '/humas/' . $path . $nama_file, $image_base64, 'public');
                        $res->respon = $singkat_sekolah . '/humas/'. $path . $nama_file;
                    }else if (($request->file('respon') != null) && ($tipe == 'file_single' || $tipe == 'file_multiple' )) {
                        $singkat_sekolah = $auth_data->sekolah_data->nm_singkat_sekolah;
                        $path = 'custom-form/' . $res->id_custom_form_sheet ;
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
                    $res->created_by = $auth_data->pengguna->id_pengguna;
                    $res->save();
                }
                
            });

            return [
                'status' => 202,
                'path' => '#' . $this->redirectBack($request->path()) . '/custom-form',
                'message' => 'Berhasil Mengirim Respon'
            ];
        }
        catch (Exception $e) {
            return [
                'status' => 300,
                'message' => 'Gagal Mengirim Respon'
            ];
        }
    }

    public function show(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $form = CustomFormSheet::with('form.form_komponen', 'form_respon.form_komponen')->where('created_by', $auth_data->pengguna->id_pengguna)->findOrFail($id);
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->form->start_time);
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $form->form->end_time);
            if ($form->form->form_settings['editable'] !== 'true') {
                throw new Exception('Form Ini Tidak Dapat Diubah');
            }
            if (
                ($form->form->jenis_custom_form === 'harian' && !$now->between($startTime->toTimeString(), $endTime->toTimeString())) ||
                ($form->form->jenis_custom_form === 'bulanan' && $now->format('d') !== $startTime->format('d') && !($now->format('d') >= $now->endOfMonth()->format('d') && $now->format('d') <= $startTime->format('d'))) ||
                ($form->form->jenis_custom_form !== 'harian' && $form->form->jenis_custom_form !== 'bulanan' && !$now->between($startTime, $endTime))
            ) {
                throw new Exception('Maaf, Form Telah Dikunci');
            }


            return view('global/custom-form/edit-respon-custom-form', compact('auth_data', 'form'));
        } catch (ModelNotFoundException $e) {
            $pesan = "Anda Tidak Memiliki Akses";
            return view('global/custom-form/edit-respon-custom-form', compact('auth_data', 'pesan'));
        } catch (Exception $e) {
            $pesan = $e->getMessage();
            return view('global/custom-form/edit-respon-custom-form', compact('auth_data', 'pesan'));
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $sheet = CustomFormSheet::with('form.form_komponen', 'form_respon.form_komponen')->where('created_by', $auth_data->pengguna->id_pengguna)->findOrFail($id);

            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $sheet->form->start_time);
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $sheet->form->end_time);

            if (($sheet->form->jenis_custom_form === 'harian' && !$now->between($startTime->toTimeString(), $endTime->toTimeString())) ||
                ($sheet->form->jenis_custom_form === 'bulanan' && $now->format('d') !== $startTime->format('d') && !($now->format('d') >= $now->endOfMonth()->format('d') && $now->format('d') <= $startTime->format('d'))) ||
                ($sheet->form->jenis_custom_form !== 'harian' && $sheet->form->jenis_custom_form !== 'bulanan' && !$now->between($startTime, $endTime))
            ) {
                throw new Exception('Gagal, Form Telah Dikunci!');
            }



            DB::transaction(function () use ($request, $input, $auth_data, $sheet, $now) {

                foreach ($input->respon as $key => $value) {
                    $sekarang = Carbon::now(env('APP_TIMEZONE', ''));
                    $res = $sheet->form_respon->where('id_custom_form_komponen', $key)->first()->update([
                        'respon' => json_encode($value),
                        'updated_at' => $sekarang,
                        'updated_by' => $auth_data->pengguna->id_pengguna
                    ]);
                }
            });
            return [
                'status' => 202,
                'path' => '#' . $this->redirectBack($request->path()) . '/custom-form/submitted/' . $sheet->id_custom_form,
                'message' => 'Berhasil Mengubah Data!'
            ];
        } catch (Exception $e) {
            return [
                'status' => 300,
                'message' => $e->getMessage()
            ];
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;

            $record = CustomFormSheet::with('form_respon')->where('created_by', $auth_data->pengguna->id_pengguna)->findOrFail($id);
            $record->form_respon()->delete();
            $record->delete();
            return [
                'status' => 200,
                'message' => 'Data Berhasil Dihapus'
            ];
        } catch (Exception $e) {
            return [
                'status' => 300,
                'message' => 'Tidak Dapat Menghapus Data!'
            ];
        }
    }

    public function getDataSiswa(Request $request){

        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;

            $request->validate([
                'id_kelas' => 'required'
            ]);

            $id_kelas = $input->id_kelas;

            $siswa = Pengguna::select('nm_pengguna')->whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            })->orderBy('nm_pengguna')->get();

            return response()->json(['data' => $siswa]);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 300,
                'message' => 'Tidak Dapat Menghapus Data!'
            ])->status(300);
        }
    }
}
