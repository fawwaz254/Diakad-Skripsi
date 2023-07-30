<?php

namespace App\Http\Controllers\administrator\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class FcmNotification extends Notification
{
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('test')
                ->setBody('test notif firebase'));
    }
}
