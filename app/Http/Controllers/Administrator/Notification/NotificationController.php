<?php

namespace App\Http\Controllers\administrator\Notification;

use App\Http\Controllers\Controller;
use App\Jobs\PushNotification;
use App\Models\Pengguna;
use Illuminate\Http\Request;

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
}
