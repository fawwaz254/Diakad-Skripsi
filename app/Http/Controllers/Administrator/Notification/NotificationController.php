<?php

namespace App\Http\Controllers\administrator\Notification;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function send(Request $request)
    {
        $pengguna = Pengguna::whereNotNull('fcm_token')->get();

        $title = "data Title";
        $body = "data Body";

        $pengguna->each(function ($p) use ($title, $body) {
            $p->notify((new FcmNotification)->with($title, $body));
        });
    }
}
