<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFollower extends Notification
{
    use Queueable;

    public function __construct(public User $follower) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'            => 'follow',
            'follower_id'     => $this->follower->id,
            'follower_name'   => $this->follower->username,
            'follower_avatar' => $this->follower->avatarUrl(),
        ];
    }
}
