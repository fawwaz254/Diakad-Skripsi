<?php

namespace App\Http\Controllers\Administrator\Notification;

use Validator;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use App\Jobs\PushNotification;
use App\Models\WhatsappGroup;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{

    public function send(Request $request)
    {
        $pengguna = Pengguna::whereNotNull('fcm_token')->get();

        $title = "data Title";
        $body = "Test Notifikasi";

        $pengguna->each(function ($p) use ($title, $body) {
            PushNotification::dispatch($p, $title, $body);
            // $p->notify((new FcmNotification)->with($title, $body));
        });
    }

    public function viewWhatsappGroup()
    {
        $list_kelas = Kelas::all();

        return view('administrator.notification.view-whatsapp-group', compact('list_kelas'));
    }

    public function fetchWhatsappGroup()
    {
        $url = env('WHATSAPP_API_GROUPS');

        $response = Http::withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post($url, ['token' => 'DSM_2023;']);
        $data = $response->json();

        $list_group = WhatsappGroup::all()->pluck('id_group')->toArray();

        foreach ($data['data'] as &$value) {
            if (in_array($value[1], $list_group)) {
                $value[] = "TERSIMPAN";
            } else {
                $value[] = "BELUM TERSIMPAN";
            }
        }
        unset($value);

        return $data;
    }

    public function actionWhatsappGroup(Request $request, $mode)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_group' => 'required',
            'nm_group' => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status_code' => 300,
                'message'     => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $new_group                      = new WhatsappGroup();
                $new_group->id_whatsapp_group   = $id;
                $new_group->id_kelas            = $input->id_kelas;
                $new_group->id_group            = $input->id_group;
                $new_group->nm_group            = $input->nm_group;
                $new_group->created_by          = $input->auth_data->pengguna->id_pengguna;
                $new_group->save();

                return [
                    'status_code'  => 202,
                    'path'    => 'notification/whatsapp',
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($mode == 'delete') {
                $data_jenis                       = WhatsappGroup::where('id_group', $input->id_group)->first();
                $data_jenis->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                $data_jenis->save();
                $data_jenis->delete();

                return [
                    'status_code' => 202,
                    'path'    => 'notification/whatsapp',
                    'message'     => 'Delete Data succesfully'
                ];
            }
        }
    }
}
