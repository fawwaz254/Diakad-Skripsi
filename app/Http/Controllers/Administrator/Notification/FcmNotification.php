<?php

namespace App\Http\Controllers\administrator\Notification;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class FcmNotification extends Notification
{
    private $title;
    private $body;

    public function with($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
        return $this;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->title)
                ->setBody($this->body));
    }
}
