<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    public function __construct(
        public User $liker,
        public Post $post
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'        => 'like',
            'liker_id'    => $this->liker->id,
            'liker_name'  => $this->liker->username,
            'liker_avatar'=> $this->liker->avatarUrl(),
            'post_id'     => $this->post->id,
            'post_thumb'  => $this->post->thumbnail()?->url(),
        ];
    }
}
