<?php

namespace App\Http\Controllers\administrator\Notification;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;


class NotificationController extends Controller
{
    // public function viewNotification(Request $request)
    // {

    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     return view('administrator/notification/view-notification', compact('auth_data'));
    // }


    public function send(Request $request)
    {
        $pengguna = Pengguna::whereNotNull('fcm_token')->get();

        $pengguna->each(function ($p) {
            $p->notify(new FcmNotification);
        });
    }
}
