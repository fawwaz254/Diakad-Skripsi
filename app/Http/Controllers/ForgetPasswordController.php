<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\Pengguna;
use App\Models\Sekolah;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Mail;
use Session;
use Validator;

class ForgetPasswordController extends BaseController
{

    public function index(Request $request)
    {

        $sekolah = Sekolah::orderBy('id_sekolah')->first();
        return view('forget-password', compact('sekolah'));

    }

    public function sendLinkResetPassword(Request $request)
    {

        $input = (object) $request->input();

        DB::beginTransaction();

        try {

            $sekolah = Sekolah::orderBy('id_sekolah')->first();
            $check = Pengguna::where('email_pengguna', $input->email)->first();

            if ($check) {

                $token = base64_encode(random_bytes(32));
                $url = "https://" . $sekolah->http_host . "/check-link-reset-password?email=" . $input->email . "&token=" . urlencode($token);
                $link_sekolah = "https://" . $sekolah->http_host;

                DB::table('pengguna_token')->insert([
                    'email' => $input->email,
                    'token' => $token,
                    'is_valid' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                DB::commit();

                $data_array = [
                    'sekolah' => $sekolah->nm_sekolah,
                    'nama' => $check->nm_pengguna,
                    'url' => $url,
                    'url_sekolah' => $link_sekolah,
                ];

                Mail::to($input->email)->send(new ForgotPasswordMail($data_array));

                // Mail::send('template-email.forget-password', ['nm_sekolah' => $sekolah->nm_sekolah, 'nama' => $check->nm_pengguna, 'url' => $url, 'link_sekolah' => $link_sekolah], function ($message) use ($input, $sekolah) {
                //     $message->to($input->email)
                //         ->from('noreply@solusimaster.co.id')
                //         ->subject('[Diakad ' . $sekolah->nm_sekolah . '] Link Reset Password');
                // });

                if (Mail::failures() != 0) {
                    return [
                        'status' => 200,
                        'message' => 'Silahkan cek email anda untuk melakukan reset password',
                    ];
                } else {
                    return [
                        'status' => 300,
                        'message' => 'Mohon maaf, email gagal terkirim',
                    ];
                }
            } else {

                return [
                    'status' => 300,
                    'message' => 'Mohon maaf email anda belum terdaftar pada sistem diakad',
                ];

            }

        } catch (Exception $e) {

            DB::rollback();

            return (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error';

        }

    }

    public function checkLinkResetPassword(Request $request)
    {

        $email = $_GET['email'];
        $token = $_GET['token'];

        $check_pengguna = Pengguna::where('email_pengguna', $email)->first();

        if ($check_pengguna) {

            $check_token = DB::table('pengguna_token')->where('token', $token)->first();

            if ($check_token) {

                Session::put('reset_email', $email);
                Session::put('reset_token', $token);
                return redirect('reset-password');

            } else {

                return redirect('/')->with('error', 'Reset password gagal , token anda tidak valid');

            }

        } else {

            return redirect('/')->with('error', 'Reset password gagal , email anda tidak ditemukan');

        }

    }

    public function resetPassword(Request $request)
    {

        if (!session('reset_email')) {
            abort(401);
        }

        $sekolah = Sekolah::orderBy('id_sekolah')->first();
        return view('reset-password', compact('sekolah'));

    }

    public function resetPasswordAction(Request $request)
    {

        if (!session('reset_email')) {
            abort(401);
        }

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:5',
                'confirmed',
            ],
        ]);

        if ($validator->fails()) {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {

            $pengguna = Pengguna::where('email_pengguna', session('reset_email'))->first();
            $pengguna->password = Hash::make($input->password);
            $pengguna->save();

            DB::table('pengguna_token')->where('token', session('reset_token'))->delete();

            $request->session()->forget('reset_email');
            $request->session()->forget('reset_token');
            $request->session()->flush();

            return [
                'status' => 201, // FAILED
                'message' => 'Selamat anda berhasil mengubah password anda',
                'link' => '/',
            ];

        }

    }

}
