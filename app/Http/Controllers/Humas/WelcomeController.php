<?php

namespace App\Http\Controllers\Humas;

use App\Models\Form;
use App\Models\JawabanForm;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\PertanyaanForm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\RoleDashboard;

use Yajra\Datatables\Datatables;
use App\Models\Setting;
use App\Models\Siswa;
use Auth;
use DB;
use Exception;
use Session;

class WelcomeController extends BaseController
{

    public function indexWelcome(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;


        if ($start_monkes = Setting::where('key_setting', 'start_monkes')->first()) {
            $start_monkes = $start_monkes->value;
        } else {
            $start_monkes = '19:00';
        }

        if ($end_monkes = Setting::where('key_setting', 'end_monkes')->first()) {
            $end_monkes = $end_monkes->value;
        } else {
            $end_monkes = '07:00';
        }
        $role_aktif = $auth_data->role_aktif;

        $role_dashboard = RoleDashboard::where(['id_role' => $role_aktif->id_role, 'is_aktif' => 1])->first();

        $forms = Form::all();

        return view('humas/welcome', compact('auth_data', 'start_monkes', 'end_monkes', 'role_dashboard', 'forms')); //folder akademik/nama file welcome.blade

    }

    public function getRekapForm(Request $request)
    {
        try {

            $input = (object) $request->input();
            $auth_data = $input->auth_data;

            $request->validate([
                'id_form' => 'required',
            ]);
            $form = Form::with('pertanyaan_form', 'jawaban_form.detail_jawaban_form.pertanyaan_form')
                ->whereHas('jawaban_form', function ($query) use ($input) {
                    if (isset($input->date)) {
                        $query->whereDate('created_at', '=', $input->date);
                    }
                })
                ->find($input->id_form);


            $kelas = Kelas::where('is_aktif', 1)->get();

            $pengguna = null;
            if (!is_null($form)) {
                if (isset($input->id_kelas)) {
                    $id_kelas = $input->id_kelas;

                    $pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
                        $q->where('aktif_status_pengguna', 1);
                    })->whereHas('siswa', function ($q) use ($id_kelas) {
                        $q->where('id_kelas', $id_kelas);
                    })->orderBy('nm_pengguna')->get();
                } else {
                    $pengguna = Pengguna::whereHas('role_pengguna', function ($q) use ($form) {
                        $q->where('id_role', $form->id_role);
                    })->whereHas('status_pengguna', function ($q) {
                        $q->where('aktif_status_pengguna', 1);
                    })->orderBy('nm_pengguna')->get();
                }
            }

            $counter = null;

            foreach ($pengguna as $user) {
                foreach ($form->jawaban_form as $jawa) {
                    if ($user->id_pengguna == $jawa->created_by) {
                        foreach ($jawa->detail_jawaban_form as $ans) {
                            if (in_array($ans->pertanyaan_form->jenis_pertanyaan, ['3', '4'])) {
                                foreach (json_decode($ans->pertanyaan_form->options) as $key => $options) {
                                    if (!isset($counter[$ans->pertanyaan_form->id_pertanyaan_form][$options])) {

                                        $counter[$ans->pertanyaan_form->id_pertanyaan_form][$options] = 0;
                                    }

                                    if (is_array(json_decode($ans->jawaban))) {
                                        if (in_array($options, json_decode($ans->jawaban))) {
                                            $counter[$ans->pertanyaan_form->id_pertanyaan_form][$options]++;
                                        }
                                    } else {
                                        if ($options == $ans->jawaban) {
                                            $counter[$ans->pertanyaan_form->id_pertanyaan_form][$options]++;
                                        }
                                    }
                                }
                            } else {
                                if (!isset($counter[$ans->pertanyaan_form->id_pertanyaan_form][$ans->jawaban])) {
                                    $counter[$ans->pertanyaan_form->id_pertanyaan_form][$ans->jawaban] = 0;
                                }
                                if (isset($counter[$ans->pertanyaan_form->id_pertanyaan_form][$ans->jawaban])) {
                                    $counter[$ans->pertanyaan_form->id_pertanyaan_form][$ans->jawaban]++;
                                }
                            }
                        }
                    }
                }
            }

            if (!isset($input->id_kelas)) {
                return response()->json([
                    'form' => $form,
                    'kelas' => $kelas,
                    'pengguna' => $form->id_role == 3 ? null : $pengguna,
                    'counter' => $counter
                ]);
            } else {
                return response()->json([
                    'form' => $form,
                    'kelas' => $kelas,
                    'pengguna' => $pengguna,
                    'counter' => $counter
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'form' => $form,
                'kelas' => $kelas,
                'pengguna' => $pengguna,
                'counter' => $counter
            ]);
        }
    }
}
